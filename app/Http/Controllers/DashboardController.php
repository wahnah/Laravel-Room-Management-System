<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user', 'room', 'customer')
            ->where([['check_in', '<=', Carbon::now()], ['check_out', '>=', Carbon::now()]])
            ->orderBy('check_out', 'ASC')
            ->orderBy('id', 'DESC')->get();

            $user = auth()->user(); // Get the authenticated user
            if ($user->role === "Customer") {
            $cust = Customer::where('user_id', $user->id)->first();
            $userReservations = Transaction::with('user', 'room', 'customer')
                ->where([['check_in', '<=', Carbon::now()], ['check_out', '>=', Carbon::now()]])
                ->where('customer_id', $cust->id)
                ->orderBy('check_out', 'ASC')
                ->orderBy('id', 'DESC')
                ->get();

                return view('dashboard.index', compact('userReservations'));
            }
        return view('dashboard.index', compact('transactions'));
    }


}
