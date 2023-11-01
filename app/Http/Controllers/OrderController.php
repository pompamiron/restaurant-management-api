<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

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
}
