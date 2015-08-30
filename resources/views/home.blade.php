@extends('app')

@section('title')
My Money
@endsection
@section('content')


  <div class="row">
  	<div class="small-12 columns">
	  	<center><h1><?php $mytime = Carbon\Carbon::now(); echo $mytime->toFormattedDateString(); ?></h1></center>
	  	@if(Session::has('message'))
  			<div class="alert alert-info" style="color: #008CBA; padding-bottom: 15px;"> {{Session::get('message')}} </div>
  		@endif
  	</div>
  </div>
  <div class="row">
  	<div class="large-4 columns">
		<div class="panel callout radius">
		  <center><h3>Income</h3>
		  <h4>${!! number_format($income, 2, '.', ',') !!}</h4></center>
		</div>
	</div>
	<div class="large-4 columns">
		<div class="panel callout radius">
			<center><h3>Profit</h3>
			<h4>${!! number_format($profit, 2, '.', ',') !!}</h4></center>
		</div>
	</div>
	  <div class="large-4 columns">
		  <div class="panel callout radius">
			  <center><h3>Cash</h3>
				  <h4>${!! number_format($cash[0]->cash, 2, '.', ',') !!}</h4></center>
			  <p>The cash that should be in your wallet.</p>
		  </div>
	  </div>
  </div>
	<div class="row" >
		<div class="large-12  columns">
			<div class="large-8 small-12 columns">
				<div id="my-cool-chart"></div>
			</div>
			<div class="large-4 small-12 columns">
				<ul data-pie-id="my-cool-chart" class="chart" >
					@foreach($spending as $spent)
						<li data-value="{{$spent['sum']}}">{{$spent['name']}}: ${{$spent['sum']}}</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	<div class="row" style="text-align: center;">
		<div class="large-6 columns">
		  <center><h3 style="color: #008CBA;">Bank Accounts</h3></center>
			@foreach($banks as $bank)
				<hr>
				<h5>{!! $bank->name !!}</h5>
				<p><a style="color: #009933;">${!! number_format($bank->balance, 2, '.', ','); !!}</a></p>
			@endforeach
		</div>
		<div class="large-6 columns">	
		  <center><h3 style="color: #008CBA;">Credit Cards</h3></center>
			@foreach($cc as $c)
				<hr>
				<h5>{!! $c->name !!}</h5>
				<div class="small-6 columns">
					<p>Balance:<br><a style="color: #009933;">${!! number_format($c->balance, 2, '.', ','); !!}</a></p>
				</div>
				<div class="small-6 columns">
					<p>Credit Limit:<br><a style="color: #009933;"> ${!! number_format($c->creditLimit, 2, '.', ','); !!}</a></p>
				</div>
			@endforeach
		</div>
	</div>
	  

<div class="row chart">
	<div class="large-12 columns">
		<center><h3 style="color: #008CBA;"><?= date("F") ?>'s Transactions</h3></center>
		<div id="example_wrapper" class="dataTables_wrapper dt-foundation no-footer">
			<table id="example" class="tdisplay dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
				<thead>
					<tr role="row">
						<th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" style="width: 25%;">Account</th>
						<th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" style="width: 20%;">Type</th>
						<th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Amount: activate to sort column ascending" style="width: 12%;">Amount</th>
						<th class="sorting show-for-large-only" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Note: activate to sort column ascending" style="width: 25%;">Note</th>
						<th class="sorting-asc show-for-large-only" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" aria-sort="ascending" style="width: 15%;">Date</th>
						<th class="sorting_disabled" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Note: activate to sort column ascending" style="width: 3%;">Edit</th>
					</tr>
				</thead>
				<tbody>
					<?php $count = 0;?>
					@foreach($transactions as $trans)
					<tr role="row" class="odd">
						@if($trans->accountID == NULL)
							<td>Cash</td>
						@else
							<td>{!! $accountNames[$trans->accountID] !!}</td>
						@endif
						@if($trans->typeID == NULL)
							<td>Payment/Transer to {!! $accountNames[$trans->ccID] !!}</td>
						@else
							<td>{!! $typeNames[$trans->typeID] !!}</td>
						@endif
						<td>${!! $trans->amount !!}</td>
						<td class="show-for-large-only">{!! $trans->note !!}</td>
						<td class="sorting_1 show-for-large-only">{!! $trans->date !!}</td>
						@if($trans->typeID == NULL)
							<td class="sorting_disabled"><a href="/edit/payment/{!! $trans->id !!}" class="button info round edit">Edit</a></td>
						@else
							<td class="sorting_disabled"><a href="/edit/{!! $trans->id !!}" class="button info round edit">Edit</a></td>
						@endif
					</tr>
					@endforeach
					@foreach($incomeData as $in)
					<tr role="row" class="odd">
						@if($in->accountID == 0)
							<td>Cash</td>
						@else
							<td>{!! $accountNames[$in->accountID] !!}</td>
						@endif
						<td>Income</td>
						<td>${!! $in->amount !!}</td>
						<td class="show-for-large-only">{!! $in->note !!}</td>
						<td class="sorting_1 show-for-large-only">{!! $in->date !!}</td>
						<td class="sorting_disabled"><a href="/edit/income/{!! $trans->id !!}" class="button info round edit">Edit</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	    Pizza.init();
$(document).ready(function(){
    $('#example').DataTable({
	    dom: 'flrtipB',
	    buttons: [
	        'pdf'
	    ],
	    "order": [ 4, 'dec' ],
	    responsive: true
	});
});
</script>
@endsection
