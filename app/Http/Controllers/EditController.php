<?php namespace App\Http\Controllers;

use DB;
use Auth;
use App\Account;
use App\Transaction;
use App\Month;
use App\Income;
use App\Payment;
use App\Transfer;
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
			$transaction->accountID = 0;
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
	
	public function paymentIndex($type, $id) {
		$user = Auth::user();
		
		if($type == 0) {
			//tansfer
			$trans = Transfer::where('id', $id)->where('userID', $user->id)->first();
		}
		else {
			//payment
			$trans = Payment::where('id', $id)->where('userID', $user->id)->first();
		}
		
		$types = DB::select('select id, name from types where userID = :id order by name ASC', ['id' => $user->id]);
		$accounts = DB::select('select id, name, accountType from accounts where userID = :id order by name ASC', ['id' => $user->id]);
		
		return view('edit-payment')->with('trans', $trans)->with('accounts', $accounts)->with('types', $types)->with('id', $id)->with('type', $type);
	}
	
	public function paymentPostIndex($type, $id) {
		$user = Auth::user();
		$date = Input::get('date');
		$amount = Input::get('amount');
		$bankID = Input::get('bank');
		$accountID = Input::get('payment');
		$note = Input::get('note');
		
		//add intial trans back onto
		if($type == 0) {
			//tansfer
			$trans = Transfer::where('id', $id)->where('userID', $user->id)->first();
			
			if($trans->creditAccountID == 0) {
				$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
				$month->cash = $month->cash + $trans->amount;
				$month->save();
			}
			else {
				$debit = Account::find($trans->creditAccountID);
				$debit->balance = $debit->balance + $trans->amount;
				$debit->save();
			}
			
			if($trans->debitAccountID == 0) {
				$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
				$month->cash = $month->cash - $trans->amount;
				$month->save();
			}
			else {
				$debit = Account::find($trans->debitAccountID);
				$debit->balance = $debit->balance + $trans->amount;
				$debit->save();
			}
		}
		else {
			//payment
			$trans = Payment::where('id', $id)->where('userID', $user->id)->first();
			//add initial payment amount back on
			if($trans->creditAccountID == 0) {
				$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
				$month->cash = $month->cash + $trans->amount;
				$month->save();
				
			}
			else {
				$credit = Account::find($trans->creditAccountID);
				$credit->balance = $credit->balance + $trans->amount;
				$credit->save();
			}
			
			//add initial payment back on
			$debit = Account::find($trans->debitAccountID);
			$debit->balance = $debit->balance + $trans->amount;
			$debit->save();
		}
		
		$trans->forceDelete();
		
		//setup as new transfer/payment
		$monthNum = substr(Input::get('date'), 0, 2);
        $month = date('M', mktime(0, 0, 0, $monthNum, 10));
		
		if($accountID == 'cash'){
            $month2 = Month::where('userID', $user->id)->where('name', $month)->first();
            $month2->cash = $month2->cash + $amount;
            $month2->save();

			if($bankID == 'cash') {
				$month2 = Month::where('userID', $user->id)->where('name', $month)->first();
                $month2->cash = $month2->cash - $amount;
                $month2->save();
			}
			else {
				$bank = Account::find($bankID);
	            $bank->balance = $bank->balance - $amount;
                $bank->save();
			}
            
            $transfer = new Transfer;
            $transfer->userID = $user->id;
            $transfer->creditAccountID = $bankID;
            $transfer->debitAccountID = 0;
            $transfer->amount = $amount;
            $transfer->note = $note;
            $transfer->date = $date;
            $transfer->month = $month;
            $transfer->save();
        }
        else {
            $cc = Account::find($accountID);
            if($bankID == 'cash') {
				$month2 = Month::where('userID', $user->id)->where('name', $month)->first();
                $month2->cash = $month2->cash - $amount;
                $month2->save();
			}
			else {
				$bank = Account::find($bankID);
	            $bank->balance = $bank->balance - $amount;
                $bank->save();
			}                

            if($cc->accountType == 'b'){
                //transfer
                $cc->balance = $cc->balance + $amount;
                $transfer = new Transfer;
                $transfer->userID = $user->id;
                $transfer->creditAccountID = $bankID;
                $transfer->debitAccountID = $accountID;
                $transfer->amount = $amount;
                $transfer->note = $note;
                $transfer->date = $date;
                $transfer->month = $month;
                $transfer->save();
            }
            else {
                //payment
                $cc->balance = $cc->balance - $amount;
                $payment = new Payment;
                $payment->userID = $user->id;
                $payment->creditAccountID = $bankID;
                $payment->debitAccountID = $accountID;
                $payment->amount = $amount;
                $payment->note = $note;
                $payment->date = $date;
                $payment->month = $month;
                $payment->save();
            }

            $cc->save();
        }

				
		return redirect('home')->with('message', 'Transfer/Payment updated successfully.');
	}
	
	public function paymentDelete($type, $id) {
		$user = Auth::user();
		$date = Input::get('date');
		$amount = Input::get('amount');
		$bankID = Input::get('bank');
		$accountID = Input::get('payment');
		$note = Input::get('note');
		
		//add intial trans back onto
		if($type == 0) {
			//tansfer
			$trans = Transfer::where('id', $id)->where('userID', $user->id)->first();
			
			if($trans->creditAccountID == 0) {
				$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
				$month->cash = $month->cash + $trans->amount;
				$month->save();
			}
			else {
				$debit = Account::find($trans->creditAccountID);
				$debit->balance = $debit->balance + $trans->amount;
				$debit->save();
			}
			
			if($trans->debitAccountID == 0) {
				$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
				$month->cash = $month->cash - $trans->amount;
				$month->save();
			}
			else {
				$debit = Account::find($trans->debitAccountID);
				$debit->balance = $debit->balance + $trans->amount;
				$debit->save();
			}
		}
		else {
			//payment
			$trans = Payment::where('id', $id)->where('userID', $user->id)->first();
			//add initial payment amount back on
			if($trans->creditAccountID == 0) {
				$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
				$month->cash = $month->cash + $trans->amount;
				$month->save();
				
			}
			else {
				$credit = Account::find($trans->creditAccountID);
				$credit->balance = $credit->balance + $trans->amount;
				$credit->save();
			}
			
			//add initial payment back on
			$debit = Account::find($trans->debitAccountID);
			$debit->balance = $debit->balance + $trans->amount;
			$debit->save();
		}
		
		$trans->forceDelete();
		
		return redirect('home')->with('message', 'Payment/Transfer deleted successfully.');
	}
	
	public function incomeIndex($id) {
		$user = Auth::user();
		
		$transaction = Income::where('id', $id)->where('userID', $user->id)->first();
		$types = DB::select('select id, name from types where userID = :id order by name ASC', ['id' => $user->id]);
		$accounts = DB::select('select id, name, accountType from accounts where userID = :id order by name ASC', ['id' => $user->id]);
		
		return view('edit-income')->with('transaction', $transaction)->with('accounts', $accounts)->with('types', $types)->with('id', $id);
	}
	
	public function incomePostIndex($id) {
		$user = Auth::user();
		$date = Input::get('date');
		$amount = Input::get('amount');
		$bankID = Input::get('bank');
		$note = Input::get('note');
		
		$income = Income::where('id', $id)->where('userID', $user->id)->first();
		if($income->accountID == 0) {
			$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
			$month->cash = $month->cash - $income->amount;
			$month->save();
		}
		else {
			$origBank = Account::find($income->accountID);
			//add original transaction value to bank account
			$origBank->balance = $origBank->balance - $income->amount;
			$origBank->save();
		}

		//update new values
		if($bankID == 'cash'){
			$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
			$month->cash = $month->cash + $income->amount;
			$month->save();
		}
		else {
			$bank = Account::find($bankID);
			$bank->balance = $bank->balance + $amount;
			$bank->save();
		}
		
		$income->date = $date;
		$income->amount = $amount;
		$income->accountID = $bankID;
		$income->note = $note;
		$income->save();
		
		return redirect('home')->with('message', 'Income updated successfully.');
	}
	
	public function incomeDelete($id) {
		$user = Auth::user();
		$date = Input::get('date');
		$amount = Input::get('amount');
		$bankID = Input::get('bank');
		$note = Input::get('note');
		
		$income = Income::where('id', $id)->where('userID', $user->id)->first();
		if($income->accountID == 0) {
			$month = Month::where('userID', $user->id)->where('name', date('M'))->first();
			$month->cash = $month->cash - $income->amount;
			$month->save();
		}
		else {
			$origBank = Account::find($income->accountID);
			//add original transaction value to bank account
			$origBank->balance = $origBank->balance - $income->amount;
			$origBank->save();
		}
		
		$income->forceDelete();
		
		return redirect('home')->with('message', 'Income deleted successfully.');
	}
	
	public function bankIndex($id) {
		$user = Auth::user();
		$bank = Account::where('id', $id)->where('userID', $user->id)->first();
		
		return view('edit-bank')->with('id', $id)->with('bank', $bank);
	}
	public function bankPostIndex($id) {
		$user = Auth::user();
		$bank = Account::where('id', $id)->where('userID', $user->id)->first();
		$bank->name = Input::get('name');
		$bank->save();
		
		return redirect('home')->with('message', 'Bank Information updated successfully.');
	}
	public function bankDelete($id) {
		
	}
	public function ccIndex($id) {
		$user = Auth::user();
		$cc = Account::where('id', $id)->where('userID', $user->id)->first();
		
		return view('edit-cc')->with('id', $id)->with('cc', $cc);
	}
	public function ccPostIndex($id) {
		$user = Auth::user();
		$cc = Account::where('id', $id)->where('userID', $user->id)->first();
		$cc->name = Input::get('name');
		$cc->creditLimit = Input::get('limit');
		$cc->statementDay = Input::get('date');
		$cc->save();
		
		return redirect('home')->with('message', 'Credit Card information updated successfully.');
	}
	public function ccDelete($id) {
		
	}
}
