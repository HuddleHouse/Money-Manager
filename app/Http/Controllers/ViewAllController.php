<?php namespace App\Http\Controllers;

use DB;
use Auth;
use App\Account;
use App\Month;
use Input;

class ViewAllController extends Controller {

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
				
		$months = DB::select('select * from month where userID = :id', ['id' => $user->id]);
		
		$spending = [];
		
		foreach($months as $month) 
		{
			$data = DB::select('SELECT tr.amount, ty.name FROM transactions tr LEFT OUTER JOIN types ty ON tr.typeID = ty.id WHERE tr.month = :month AND tr.year = :year ', ['month' => $month->name, 'year' => $month->year]);
			
			$tmp = [];
			$tmp2 = ['month' => $month->name, 'year' => $month->year, 'trans' => []];
			foreach($data as $d)
			{
				if(isset($tmp[$d->name]))
				{
					$tmp[$d->name]['amount'] += $d->amount;
				}
				else 
				{
					$tmp[$d->name]['amount'] = $d->amount;
					$tmp[$d->name]['name'] = $d->name;
				}
			}
			
			foreach($tmp as $t)
			{
				array_push($tmp2['trans'],['amount' => $t['amount'], 'name' => $t['name']]);
			}
			arsort($tmp2['trans']);
			array_push($spending, $tmp2);
		}
		
		return view('view-all')->with('spending', $spending);
	}
	

}
