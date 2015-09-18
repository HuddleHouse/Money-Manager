<?php namespace App\Http\Controllers;

use DB;
use Auth;
use App\Account;
use App\Month;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		//add check for row in month table with correct month
		//make it if not there.
		
		$user = Auth::user();
		$month = date("M");
		$year = date('Y');
		
		$banks = DB::select('select * from accounts where userID = :id and accountType = "b"', ['id' => $user->id]);
		$cc = DB::select('select * from accounts where userID = :id and accountType = "c"', ['id' => $user->id]);
		$credits = DB::select('select * from income where userID = :id and month = :month and year = :year', ['id' => $user->id, 'month' => $month, 'year' => $year]);
		$accounts = DB::select('select * from accounts where userID = :id', ['id' => $user->id]);
		$debits = DB::select('select * from transactions where userID = :id and month = :month and year = :year', ['id' => $user->id, 'month' => $month, 'year' => $year]);
		$transfers = DB::select('select * from transfers where userID = :id and month = :month and year = :year', ['id' => $user->id, 'month' => $month, 'year' => $year]);
		$payments = DB::select('select * from payments where userID = :id and month = :month and year = :year', ['id' => $user->id, 'month' => $month, 'year' => $year]);
		
		$accountNames = [];	
		foreach($accounts as $account) {
			$accountNames = $accountNames + [$account->id => $account->name];
		}
		
		
		$income = 0;
		$spent = 0;
		
		//sum  up for months profit
		foreach($credits as $credit){
			$income += $credit->amount;
		}
		foreach($debits as $debit){
			if($debit->accountID == 0 && $debit->month == date("M")){
				$spent = $spent + $debit->amount;
			}
			else {
				//check to make sure teh cc id is not a bank if so this is a transfer and doesn't get counted
				$account2 = Account::find($debit->accountID);
				if($account2->accountType == 'b' && $debit->month == date("M")){
					//this is a payment on a cc
					$spent = $spent + $debit->amount;
				}
			}
		}
		foreach($payments as $payment){
			if($payment->month == date("M")) {
				$spent = $spent + $payment->amount;
			}
		}
		$profit = $income - $spent;
		
		$monthData = Month::where('userID', $user->id)->where('name', date("M"))->where('year', date("Y"))->first();
		$monthData->income = $income;
		$monthData->profit = $profit;
		$monthData->save();
		
		//prepare spending amounts for types for the pie chart.
		$types = DB::select('select * from types where userID = :id order by name ASC', ['id' => $user->id]);
		$spending = [];
		$typeNames = [];

		foreach($types as $type){
			$typeNames = $typeNames + [$type->id => $type->name];
			$tmp = DB::select('select * from transactions where userID = :id and month = :month and typeID = :typeID and year = :year', ['id' => $user->id, 'month' => $month, 'typeID' => $type->id, 'year' => $year]);
			$sum = 0;
			foreach($tmp as $i){
				$sum = $sum + $i->amount;
			}
			if($sum != 0){
				array_push($spending, ["sum" => $sum, "name"=>$type->name]);
			}
		}
		arsort($spending);
		
		$cash = DB::select('select cash from month where userID = :id and name = :month and year = :year', ['id' => $user->id, 'month' => $month, 'year' => date("Y")]);
		
		return view('home')->with('banks', $banks)->with('cc', $cc)->with('income', $income)->with('profit', $profit)->with('spending', $spending)->with('cash', $cash)->with('accountNames', $accountNames)->with('typeNames', $typeNames)->with('transactions', $debits)->with('incomeData', $credits)->with('payments', $payments)->with('transfers', $transfers);
	}
	

}
