<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Room;
use App\Models\User;
use App\Notifications\NewFoodOrderNotification;
use App\Events\RefreshDashboardEvent;
use App\Events\NewFoodOrderEvent;

class OrderController extends Controller
{
    public function store(Request $request, Customer $customer, Room $room)
    {
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:menu_items,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        $user = auth()->user();
        $cust = Customer::where('user_id', $user->id)->first();
        // Create a new order
        $order = Order::create([
            'customer_id' => $cust->id, // Assuming you have a User model for customers
            'total_price' => 0, // We'll calculate the total price below
        ]);

        $totalPrice = 0;
        $orderItems = [];

        foreach ($validatedData['items'] as $itemId) {
            $menuItem = MenuItem::findOrFail($itemId);
            $quantity = $validatedData['quantities'][$itemId];

            $orderItems[] = [
                'name' => $menuItem->name,
                'quantity' => $quantity,
            ];

            // Calculate item total and add to the order total price
            $itemTotal = $menuItem->price * $quantity;
            $totalPrice += $itemTotal;

            // Attach the menu item to the order with quantity
            $order->items()->attach($itemId, ['quantity' => $quantity, 'item_total' => $itemTotal]);
        }

        // Update the order's total price
        $order->update(['total_price' => $totalPrice]);
        $superAdmins = User::whereIn('role', ['Super', 'Admin'])->get();

        foreach ($superAdmins as $superAdmin) {
            $message = 'food order notification from ' . $room->number;
            event(new NewFoodOrderEvent($message, $superAdmin));
            $superAdmin->notify(new NewFoodOrderNotification($customer, $room, $totalPrice, $orderItems));
        }

        event(new RefreshDashboardEvent("Someone placed a food order"));

        return view('customer.order_success');
    }
}
