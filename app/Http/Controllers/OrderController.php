<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Order::with('items', 'user');
    
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }
    
            $orders = $query->get();
    
            $orders = $orders->map(function ($order) {
                $order->user_name = $order->user->name;
                unset($order->user);
                return $order;
            });
    
            return response()->json($orders, 200);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            // Handle model not found errors
            return response()->json(['message' => 'Order not found'], 404);
        } catch (\Exception $e) {
            // Handle other unexpected errors
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['items.menuItem'])->findOrFail($id);
            return response()->json($order, 200);
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
    
        // Get the authenticated user and their user ID
        $user = Auth::user();
        $userId = $user->id;
    
        // Create the order
        $order = Order::create([
            'user_id' => $userId,
            'description' => $request->input('description'),
            'order_type' => $request->input('order_type'),
            'status' => $request->input('status'),
            'payment_status' => $request->input('payment_status'),
            'payment_method' => $request->input('payment_method'),
        ]);
    
        // Create order items
        $items = $request->input('items');
        foreach ($items as $itemData) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $itemData['menu_item_id'],
                'quantity' => $itemData['quantity'],
                'description' => $itemData['description'],
            ]);
        }
    
        return response()->json(['message' => 'Order created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        // Check if the order with the given ID exists
        $order = Order::find($id);
    
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        $request->validate([
            'description' => 'string',
            'order_type' => 'string',
            'status' => 'string',
            'payment_status' => 'string',
            'payment_method' => 'string',
            'items' => 'array',
        ]);
    
        // Update the order attributes (if provided)
        if ($request->has('description')) {
            $order->description = $request->input('description');
        }
        if ($request->has('order_type')) {
            $order->order_type = $request->input('order_type');
        }
        if ($request->has('status')) {
            $order->status = $request->input('status');
        }
        if ($request->has('payment_status')) {
            $order->payment_status = $request->input('payment_status');
        }
        if ($request->has('payment_method')) {
            $order->payment_method = $request->input('payment_method');
        }
    
        $order->save();
    
        // Process order items (add, update, or delete as needed)
        $items = $request->input('items');
        $updatedItemIds = [];
    
        foreach ($items as $itemData) {
            if (isset($itemData['id'])) {
                // If the item has an 'id', update the existing item
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
                // If the item does not have an 'id', create a new item
                $newItem = OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $itemData['menu_item_id'],
                    'quantity' => $itemData['quantity'],
                    'description' => $itemData['description'],
                ]);
    
                $updatedItemIds[] = $newItem->id;
            }
        }
    
        // Delete items that were not updated or created in this request
        $order->items()->whereNotIn('id', $updatedItemIds)->delete();
    
        return response()->json(['message' => 'Order updated successfully'], 200);
    }
}
