<?php namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Transaction;
use Input;
use App\Type;
use App\Account;
use App\Month;
use App\Income;
use App\Transfer;
use App\Payment;

class SettingsController extends Controller {

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
		$user = Auth::user();

		$types = DB::select('select id, name from types where userID = :id order by name ASC', ['id' => $user->id]);
		$accounts = DB::select('select id, name, accountType from accounts where userID = :id order by name ASC', ['id' => $user->id]);

		return view('settings')->with('types', $types)->with('accounts', $accounts);
	}


	public function postIndex()
	{
		$user = Auth::user();
        $id = Input::get('payment');

		/*
		*There is an invisible field called form that tells what form was submitted
		*
		*/
		if(Input::get('form') == 'trans'){
            $monthNum = substr(Input::get('date'), 0, 2);
            $month = date('M', mktime(0, 0, 0, $monthNum, 10));
            $year = substr(Input::get('date'), -4);

			$transaction = new Transaction;
			$transaction->userID = $user->id;
			$transaction->date = Input::get('date');
			$transaction->amount = Input::get('amount');
			$transaction->typeID = Input::get('type');
			$transaction->note = Input::get('note');
			$transaction->year = $year;
			$transaction->month = $month;

			if($id == 'cash'){
				$month = Month::where('userID', $user->id)->where('name', $month)->where('year', date("Y"))->first();
                $month->cash -= Input::get('amount');
                $month->save();
                $transaction->accountID = 0;
			}
			else {
				$transaction->accountID = $id;
				$account = Account::find($id);

				//add for credit subtract for bank
				if($account->accountType == 'c'){
					$account->balance += Input::get('amount');
				}
				else {
					$account->balance -= Input::get('amount');
				}

				$account->save();
			}

			$transaction->save();
			return redirect('options')->with('message', 'Transaction added successfully.');
		}
		else if(Input::get('form') == 'type'){
			$type = new Type;
			$type->userID = $user->id;
			$type->name = Input::get('name');
			$type->save();
			return redirect('options')->with('message', 'Category added successfully.');
		}
        else if(Input::get('form') == 'payment'){
            $monthNum = substr(Input::get('date'), 0, 2);
            $month = date('M', mktime(0, 0, 0, $monthNum, 10));
            $year = substr(Input::get('date'), -4);
            $amount = Input::get('amount');
            $bankID = Input::get('bank');
            $ccID = Input::get('payment');
            $note = Input::get('note');
			$date = Input::get('date');

            if($ccID == 'cash'){
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
                $transfer->year = $year;
                $transfer->month = $month;
                $transfer->save();
            }
            else {
	            $cc = Account::find($ccID);

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
	                $transfer->debitAccountID = $ccID;
	                $transfer->amount = $amount;
	                $transfer->note = $note;
	                $transfer->date = $date;
	                $transfer->year = $year;
	                $transfer->month = $month;
	                $transfer->save();
                }
                else {
	                //payment
	                $cc->balance = $cc->balance - $amount;
	                $payment = new Payment;
	                $payment->userID = $user->id;
	                $payment->creditAccountID = $bankID;
	                $payment->debitAccountID = $ccID;
	                $payment->amount = $amount;
	                $payment->note = $note;
	                $payment->date = $date;
	                $payment->year = $year;
	                $payment->month = $month;
	                $payment->save();
                }

                $cc->save();
            }

            return redirect('options')->with('message', 'Payment saved successfully.');
        }
		else if(Input::get('form') == 'cc'){
			$cc = new Account;
			$cc->userID = $user->id;
			$cc->name = Input::get('name');
			$cc->balance = Input::get('balance');
			$cc->creditLimit = Input::get('limit');
			$cc->statementDay = Input::get('date');
			$cc->accountType = 'c';
			$cc->save();

			return redirect('options')->with('message', 'Credit Card added successfully.');
		}
		else if(Input::get('form') == 'bank'){
			$bank = new Account;
			$bank->userID = $user->id;
			$bank->name = Input::get('name');
			$bank->balance = Input::get('balance');
			$bank->accountType = 'b';
			$bank->save();

			return redirect('options')->with('message', 'Bank Account added successfully.');
		}
        else if(Input::get('form') == 'income'){
            $date = Input::get('date');
            $monthNum = substr($date, 0, 2);
            $month = date('M', mktime(0, 0, 0, $monthNum, 10));
            $amount = Input::get('amount');
            $bankID = Input::get('bank');
            $year = substr(Input::get('date'), -4);

            $income = new Income;
            $income->userID = $user->id;
            $income->month = $month;
            $income->amount = $amount;
            $income->note = Input::get('note');
            $income->date = $date;
            $income->year = $year;

            if($bankID == "cash"){
                $income->accountID = 0;
                $month = Month::where('userID', $user->id)->where('name', $month)->first();
                $month->cash = $month->cash + $amount;
                $month->save();
            }
            else{
                $income->accountID = $bankID;
                $bank = Account::find($bankID);
                $bank->balance = $bank->balance + $amount;
                $bank->save();
            }
            $income->save();
            return redirect('options')->with('message', 'Income added successfully.');

        }
	}


}
