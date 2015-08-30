@extends('app')

@section('title')
My Money - Settings
@endsection

@section('content')

	<div class="row">
		<div class="large-6 small-10 small-offset-1 columns">
	  	<h1>Settings</h1>
	  		@if(Session::has('message'))
	  			<div class="alert alert-info" style="color: #008CBA;"> {{Session::get('message')}} </div>
	  		@endif
		</div>
	</div>
	<div class="row" style="padding-bottom: 40px;">
		<div class="large-6 small-12 columns">
			<hr>
			<a href="#" data-reveal-id="transaction" class="button expand round">Add a Credit (spending)</a>
			<hr>
			<a href="#" data-reveal-id="payment" class="button expand round">Add Payment/Transfer</a>
			<hr>
			<a href="#" data-reveal-id="income" class="button expand round">Add Income</a>
		</div>
		<div class="large-6 small-12 columns">
			<hr>
			<a href="#" data-reveal-id="cc" class="button expand round">Add a Credit Card</a>
			<hr>
			<a href="#" data-reveal-id="bank" class="button expand round">Add a Bank Account</a>
			<hr>
			<a href="#" data-reveal-id="type" class="button expand round">Add a Spending Category</a>
		</div>
	</div>

<div id="income" class="reveal-modal large" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h2 id="modalTitle">Add Income.</h2>
<div class="row">

<form class="form-horizontal" id="transForm" role="form" method="POST" action="/options">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<input type="radio" name="form" value="income" checked="true" style="display: none;">
	<div class="row">
		<div class="large-6 columns">
	      <label>Date
	        <input type="datetime" class="ddatepicker" value="<?php $mytime = Carbon\Carbon::now(); echo $mytime->format('m/d/Y'); ?>" name="date" data-date-format="mm/dd/yy" id="dp">
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Amount
	        <input type="number" step="any" name="amount" placeholder="$5.00" />
	      </label>
	    </div>
	</div>

	<div class="row">

	    <div class="large-12 columns" style="margin-bottom: 15px;">
		  <label>Bank Account
		    <select name="bank">
				@foreach($accounts as $account)
					@if($account->accountType == 'b')
					<option value="{{ $account->id }}">{{ $account->name }}</option>
					@endif
				@endforeach
					<option value="cash">Cash</option>
		    </select>
		  </label>
		</div>
	</div>
	<div class="row">
	    <div class="large-12 columns" style="padding-top: 15px;">
	      <label>Note
	        <textarea placeholder="Details..." name="note"></textarea>
	      </label>
	    </div>
    </div>
    <div class="row">
    	<div class="large-6 columns" style="padding-top: 20px;">
			<input type="submit" class="button" value="Add Transaction">
    	</div>
    </div>
	</form>


</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

<div id="payment" class="reveal-modal large" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h2 id="modalTitle">Payment/Transfer</h2>
<div class="row">

<form class="form-horizontal" id="transForm" role="form" method="POST" action="/options">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<input type="radio" name="form" value="payment" checked="true" style="display: none;">
	<div class="row">
		<div class="large-6 columns">
	      <label>Date
	        <input type="datetime" class="ddatepicker" value="<?php $mytime = Carbon\Carbon::now(); echo $mytime->format('m/d/Y'); ?>" name="date" data-date-format="mm/dd/yy" id="dp">
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Amount
	        <input type="number" step="any" name="amount" placeholder="$5.00" />
	      </label>
	    </div>
	</div>

	<div class="row">

	    <div class="large-6 columns" style="margin-bottom: 15px;">
		  <label>Bank Account
		    <select name="bank">
				@foreach($accounts as $account)
					@if($account->accountType == 'b')
					<option value="{{ $account->id }}">{{ $account->name }}</option>
					@endif
				@endforeach
		    </select>
		  </label>
		</div>

	    <div class="large-6 columns">
		  <label>Credit Account
		    <select name="payment">
				@foreach($accounts as $account)
					<option value="{{ $account->id }}">{{ $account->name }}</option>
				@endforeach
				<option value="cash">Cash</option>
		    </select>
		  </label>
		</div>
	</div>
	<div class="row">
	    <div class="large-12 columns" style="padding-top: 15px;">
	      <label>Note
	        <textarea placeholder="Details..." name="note"></textarea>
	      </label>
	    </div>
    </div>
    <div class="row">
    	<div class="large-6 columns" style="padding-top: 20px;">
			<input type="submit" class="button" value="Add Transaction">
    	</div>
    </div>
	</form>


</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

<div id="transaction" class="reveal-modal large" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h2 id="modalTitle">Add a Credit.</h2>
<div class="row">

<form class="form-horizontal" id="transForm" role="form" method="POST" action="/options">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<input type="radio" name="form" value="trans" checked="true" style="display: none;">
	<div class="row">
		<div class="large-6 columns">
	      <label>Date
	        <input type="datetime" class="ddatepicker" value="<?php $mytime = Carbon\Carbon::now(); echo $mytime->format('m/d/Y'); ?>" name="date" data-date-format="mm/dd/yy" id="dp">
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Amount
	        <input type="number" step="any" name="amount" placeholder="$5.00" />
	      </label>
	    </div>
	</div>

	<div class="row">

	    <div class="large-6 columns" style="margin-bottom: 15px;">
		  <label>Category
		    <select name="type">
				@foreach($types as $type)
					<option value="{{ $type->id }}">{{ $type->name }}</option>
				@endforeach
		    </select>
		  </label>
		</div>

	    <div class="large-6 columns">
		  <label>Account
		    <select name="payment">
				@foreach($accounts as $account)
					<option value="{{ $account->id }}">{{ $account->name }}</option>
				@endforeach
					<option value="cash">Cash</option>
		    </select>
		  </label>
		</div>
	</div>
	<div class="row">
	    <div class="large-12 columns" style="padding-top: 15px;">
	      <label>Note
	        <textarea placeholder="Details..." name="note"></textarea>
	      </label>
	    </div>
    </div>
    <div class="row">
    	<div class="large-6 columns" style="padding-top: 20px;">
			<input type="submit" class="button" value="Add Transaction">
    	</div>
    </div>
	</form>


</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

<div id="cc" class="reveal-modal medium" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h2 id="modalTitle">Add a Credit Card.</h2>
<div class="row">

<form class="form-horizontal" role="form" method="POST" action="/options">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<input type="radio" name="form" value="cc" checked="true" style="display: none;">
	<div class="row">
		<div class="large-6 columns">
	      <label>Name
	        <input type="text" name="name" />
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Account Balance
	        <input type="text" name="balance" placeholder="$5.00" />
	      </label>
	    </div>
	</div>

	<div class="row">
		<div class="large-6 columns">
	      <label>Credit Limit
	        <input type="text" name="limit" placeholder="$2,000"/>
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Statement Date
	        <input type="text"  placeholder="Day of monthly statement." name="date">
	      </label>
	    </div>

	</div>
    <div class="row">
    	<div class="large-6 columns" style="padding-top: 20px;">
			<input type="submit" class="button" value="Add Credit Card">
    	</div>
    </div>
	</form>


</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

<div id="bank" class="reveal-modal medium" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h2 id="modalTitle">Add a Bank Account.</h2>
<div class="row">

<form class="form-horizontal" role="form" method="POST" action="/options">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<input type="radio" name="form" value="bank" checked="true" style="display: none;">
	<div class="row">
		<div class="large-6 columns">
	      <label>Name
	        <input type="text" name="name" />
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Account Balance
	        <input type="text" name="balance" placeholder="$500.00" />
	      </label>
	    </div>
	</div>

    <div class="row">
    	<div class="large-6 columns" style="padding-top: 20px;">
			<input type="submit" class="button" value="Add Bank Account">
    	</div>
    </div>
	</form>


</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

<div id="type" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<h2 id="modalTitle">Add a Spending Category.</h2>
<div class="row">

<form class="form-horizontal" role="form" method="POST" action="/options">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<input type="radio" name="form" value="type" checked="true" style="display: none;">
	<div class="row">
		<div class="large-12 columns">
	      <label>Name
	        <input type="text" name="name" />
	      </label>
	    </div>
	</div>

    <div class="row">
    	<div class="large-6 columns" style="padding-top: 20px;">
			<input type="submit" class="button" value="Add Category">
    	</div>
    </div>
	</form>


</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

@endsection
