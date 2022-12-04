<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\SalaryBillDetail;
use App\Models\Year;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        if (auth()->user()->is_admin) {
            return '/admin';
        }

        return '/home';
    }

    public function showLoginForm()
    {
        $years = Year::latest('financial_year')->where('active', 1);
        $curyear = $years->take(1)->value('financial_year');
        // $years = $years->pluck('financial_year', 'id');

        $allocations = Allocation::with('year')
           ->whereHas('year', function ($query) use ($curyear) {
               $query->where('financial_year', $curyear);
           });

        $salaryBillDetails = SalaryBillDetail::latest()->with(['year', 'created_by'])
                   ->whereHas('year', function ($query) use ($curyear) {
                       $query->where('financial_year', $curyear);
                   })->get();

        $fields = ['pay', 'da', 'hra', 'other', 'ota'];

        $allocation = [];
        $total = [];
        $balance = [];

        foreach ($fields as $field) {
            $allocation[$field] = $allocations->sum($field);
            $total[$field] = $salaryBillDetails->sum($field);
            $balance[$field] = $allocation[$field] - $total[$field];
        }

        return view('auth.login', compact('curyear', 'allocation', 'total', 'balance'));
    }

    public function username()
    {
        return 'name';
    }
}
