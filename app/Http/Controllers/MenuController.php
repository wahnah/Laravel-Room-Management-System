<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Customer;
use App\Models\Room;

class MenuController extends Controller
{
    public function index(Customer $customer, Room $room)
    {
        $menuItems = MenuItem::all();
        return view('customer.menu', compact('menuItems', 'customer','room'));
    }
}
