<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Month;
use DB;

class UpdateMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateMonth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the Month column for all users.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    //This creates a new Entry in the Month table and carries over the data from last month.
	    
       $months = DB::select('select * from month where name = :month and year = :year', ['month' => date("M"), 'year' => date("Y")]);
       
       if($months) {
		    
       }
       else {
	       $months = DB::select('select * from month');
	       foreach($months as $month){
		       $new = New Month;
		       $new->userID = $month->userID;
		       $new->cash = $month->cash;
		       $new->year = date("Y");
		       $new->name = date("M");
		       $new->save();
	       }
       }
    }
}
