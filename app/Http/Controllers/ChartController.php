<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function dialyGuestPerMonth(Request $request)
{
    $selectedDate = $request->input('selected_date');
    if (!$selectedDate) {
        $selectedDate = Carbon::now()->format('Y-m');



    }
    $year = Carbon::parse($selectedDate)->format('Y');
    $month = Carbon::parse($selectedDate)->format('m');


        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $day_array = array();
        $guests_count_array = array();

        for ($i = 1; $i <= $days_in_month; $i++) {
            array_push($day_array, $i);
            array_push($guests_count_array, $this->countGuestsPerDay($year, $month, $i));
        }

        $max_no = max($guests_count_array);
        $max = round(($max_no + 10 / 2) / 10) * 10;

        $dialyGuestPerMonth = array(
            'day' => $day_array,
            'guest_count_data' => $guests_count_array,
            'max' => $max
        );
        //dd($selectedDate, $year, $month);
        //$chartContent = view('dashboard.partials.chart_content', compact('dialyGuestPerMonth', 'year', 'month'))->render();


            return response()->json(['data' => $dialyGuestPerMonth, 'month' => $month, 'year' => $year]);



        //return $dialyGuestPerMonth;
    }

    private function countGuestsPerDay($year, $month, $day)
    {
        $time = strtotime($month . '/' . $day . '/' . $year);
        $date = date('Y-m-d', $time);

        $room_count = Transaction::where([['check_in', '<=', $date], ['check_out', '>=', $date]])->count();

        return $room_count;
    }

    public function dialyGuest($year,$month,$day)
    {
        $time = strtotime($month . '/' . $day . '/' . $year);
        $date = date('Y-m-d', $time);

        $transactions = Transaction::where([['check_in', '<=', $date], ['check_out', '>=', $date]])->get();

        return view('dashboard.chart_detail', compact('transactions','date'));
    }


    public function incomePerMonth(Request $request)
    {
        $selectedDate = $request->input('selected_datee');
        if (!$selectedDate) {
            $selectedDate = Carbon::now()->format('Y-m');



        }
        $year = Carbon::parse($selectedDate)->format('Y');
        $month = Carbon::parse($selectedDate)->format('m');

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $day_array = array();
        $income_array = array();

        for ($i = 1; $i <= $days_in_month; $i++) {
            array_push($day_array, $i);
            array_push($income_array, $this->calculateIncomePerDay($year, $month, $i));
        }

        $total_income = 0;

    for ($i = 1; $i <= $days_in_month; $i++) {
        $total_income += $this->calculateIncomePerDay($year, $month, $i);
    }

        $max_no = max($income_array);
        $max = round(($max_no + 100 / 2) / 100) * 100;

        $incomePerMonth = array(
            'day' => $day_array,
            'income_data' => $income_array,
            'max' => $max
        );

        return response()->json(['data' => $incomePerMonth, 'month' => $month, 'year' => $year, 'total_income' => $total_income]);

        //return $incomePerMonth;
    }

    private function calculateIncomePerDay($year, $month, $day)
    {
        $time = strtotime($month . '/' . $day . '/' . $year);
        $date = date('Y-m-d', $time);

        $total_income = Payment::whereDate('created_at', $date)->sum('price');

        return $total_income;
    }

    public function dailyIncome($year, $month, $day)
    {
        $time = strtotime($month . '/' . $day . '/' . $year);
        $date = date('Y-m-d', $time);

        $transactions = Payment::whereDate('created_at', $date)
            ->get();

        return view('dashboard.income_chart_detail', compact('transactions', 'date'));
    }
}
