<?php

namespace App\Http\Middleware;

use App\Models\MonthlyPay;
use App\Models\SoftSetting;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CheckSystem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $softCheck = SoftSetting::first();

        $monthlyPayDue = MonthlyPay::where("status", 0)->get();
        if ($monthlyPayDue->count() > 0) {

            $currntDay = Carbon::now();
            $dueDay = config('app.due_date');
            $totalDueData = Carbon::now()->startOfMonth()->addDay($dueDay);

            $dayresult = $currntDay->gt($totalDueData);

            if ($dayresult) {
                $softCheck->status = 0;
                $softCheck->save();
            }
        }

        if ($softCheck->status == 0) {
            $adminUser = Auth::guard('admin')->user();
            if (!empty($adminUser)) {
                $payments = MonthlyPay::paginate(8);

                $startData = Carbon::now()->startOfMonth();
                $endData = Carbon::now()->endOfMonth();
                $currentMonthPay = MonthlyPay::whereBetween('created_at', [$startData, $endData])->get()->first();
                // $currentMonthPay = (object) [
                //     "status" => "",
                // ];
                return response()->view('pages.admin.payment', compact('payments', 'currentMonthPay'));
            }
            $teacher = Auth::guard('teacher')->user();
            if (!empty($teacher)) {

                return redirect()->route('login')->with(['error' => "Error in Card Information"]);

            }

        } else {
            return $next($request);
        }

    }
}
