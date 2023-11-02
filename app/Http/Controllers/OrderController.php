<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Order::with('items.menuItem', 'user');
    
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }
    
            $orders = $query->get();
    
            $orders->each(function ($order) {
                $order->user_name = $order->user->name;
                unset($order->user);
            });
    
            return response()->json(['data' => $orders], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['items.menuItem'])->findOrFail($id);
            return response()->json(['data' => $order], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_type' => 'required|string',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'items' => 'required|array',
        ]);

        $user = Auth::user();

        $orderData = $request->only([
            'description',
            'order_type',
            'status',
            'payment_status',
            'payment_method',
        ]);

        $orderData['user_id'] = $user->id;
    
        $order = Order::create($orderData);

        $this->processOrderItems($order, $request->input('items'));
    
        return response()->json(['message' => 'Order created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'order_type' => 'string',
            'status' => 'string',
            'payment_status' => 'string',
            'payment_method' => 'string',
            'items' => 'array',
        ]);

        $orderData = $request->only([
            'description',
            'order_type',
            'status',
            'payment_status',
            'payment_method',
        ]);

        $order->update($orderData);

        $this->processOrderItems($order, $request->input('items'));
    
        return response()->json(['message' => 'Order updated successfully'], 200);
    }

    private function processOrderItems(Order $order, array $items)
    {
        $updatedItemIds = [];
    
        foreach ($items as $itemData) {
            if (isset($itemData['id'])) {
                $orderItem = OrderItem::find($itemData['id']);
    
                if ($orderItem) {
                    $orderItem->update([
                        'menu_item_id' => $itemData['menu_item_id'],
                        'quantity' => $itemData['quantity'],
                        'description' => $itemData['description'],
                    ]);
    
                    $updatedItemIds[] = $orderItem->id;
                }
            } else {
                $newItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $itemData['menu_item_id'],
                    'quantity' => $itemData['quantity'],
                    'description' => $itemData['description'],
                ]);
    
                $updatedItemIds[] = $newItem->id;
            }
        }
    
        $order->items()->whereNotIn('id', $updatedItemIds)->delete();
    }
}
