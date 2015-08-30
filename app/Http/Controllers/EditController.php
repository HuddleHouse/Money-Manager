<?php namespace App\Http\Controllers;

use DB;
use Auth;
use App\Account;
use App\Transaction;
use App\Month;
use Input;

class EditController extends Controller {

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
	public function index($id)
	{
		$user = Auth::user();
		
		$transaction = Transaction::where('id', $id)->where('userID', $user->id)->first();
		$types = DB::select('select id, name from types where userID = :id order by name ASC', ['id' => $user->id]);
		$accounts = DB::select('select id, name, accountType from accounts where userID = :id order by name ASC', ['id' => $user->id]);
		
		return view('edit')->with('transaction', $transaction)->with('accounts', $accounts)->with('types', $types)->with('id', $id);
	}
	
	public function postIndex($id)
	{
		$user = Auth::user();
		$date = Input::get('date');
		$amount = Input::get('amount');
		$type = Input::get('type');
		$accountID = Input::get('payment');
		$note = Input::get('note');
		
		$transaction = Transaction::where('id', $id)->where('userID', $user->id)->first();
		if($accountID != 'cash'){
			$account = Account::find($accountID);
		}
	
		$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
		
		//if they changed the account that was affected get the original
		if($accountID != $transaction->accountID){
			if($transaction->accountID){
				$origAccount = Account::find($transaction->accountID);
				if($origAccount->accountType == 'c'){
					$origAccount->balance = $origAccount->balance - $transaction->amount;
				}
				else {
					$origAccount->balance = $origAccount->balance + $transaction->amount;
				}
				$origAccount->save();
			}
			else {
				//if here the original payment was cash            
                $month->cash = $month->cash + $amount;
			}
			//add or subtract original amount for transaction
			//subtract for credit card add for bank account
			
		}
		else {
			//add or subtract original amount for transaction
			//subtract for credit card add for bank account
			if($accountID != 'cash'){
				if($account->accountType == 'c'){
					$account->balance = $account->balance - $transaction->amount;
				}
				else {
					$account->balance = $account->balance + $transaction->amount;
				}
			}
			else {
				//if here the original payment was cash
                $month->cash = $month->cash + $amount;
			}
		}
		
		//now update the transaction amount with the new amount
		$transaction->amount = $amount;
		
		
		//update the account with the new balance
		if($accountID != 'cash'){
			if($account->accountType == 'c'){
				$account->balance = $account->balance + $transaction->amount;
			}
			else {
				$account->balance = $account->balance - $transaction->amount;
			}
			$account->save();
			$transaction->accountID = $accountID;
		}
		else {
			//if here the original payment was cash
			$transaction->accountID = NULL;
            $month->cash = $month->cash - $amount;
		}
		$month->save();
		
		//update the rest of the account information
		$transaction->date = $date;
		$transaction->typeID = $type;
		$transaction->note = $note;
		$transaction->save();
		
		return redirect('home')->with('message', 'Transaction updated successfully.');
	}
	
	public function deleteTransaction($id)
	{
		$user = Auth::user();
		$transaction = Transaction::where('id', $id)->where('userID', $user->id)->first();
		$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
		
		if($transaction->accountID){
			$account = Account::find($transaction->accountID);
			if($account->accountType == 'c'){
				$account->balance = $account->balance - $transaction->amount;
			}
			else {
				$account->balance = $account->balance + $transaction->amount;
			}
			$account->save();
		}
		else {
			//if here the original payment was cash
            $month->cash = $month->cash + $amount;
		}
		$month->save();
		$transaction->forceDelete();
		
		return redirect('home')->with('message', 'Transaction deleted successfully.');
	}
	
	public function paymentIndex($id) {
		
	}
	
	public function paymentPostIndex($id) {
		
	}
	
	public function incomeIndex($id) {
		
	}
	
	public function incomePostIndex($id) {
		
	}
}
