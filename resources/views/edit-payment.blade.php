@extends('app')

@section('title')
My Money
@endsection
@section('content')
<div class="row" style="margin-top: 50px;">
	<center><h1>Edit Payment/Transfer</h1></center>
	<div class="large-10 large-offset-1 columns">
	<form class="form-horizontal" id="transForm" role="form" method="POST" action="/edit/payment/{!! $type !!}/{!! $id !!}">
	<input type="hidden" name="_token" value="{!! csrf_token() !!}">
	<input type="radio" name="form" value="payment" checked="true" style="display: none;">
		<div class="row">
			<div class="large-6 columns">
		      <label>Date
		        <input type="datetime" class="ddatepicker" value="{!! $trans->date !!}" name="date" data-date-format="mm/dd/yyyy" id="dp">
		      </label>
		    </div>
		    <div class="large-6 columns">
		      <label>Amount (No commas)
		        <input type="number" step="any" name="amount" placeholder="$5.00" value="{!! $trans->amount !!}"/>
		      </label>
		    </div>
		</div>
	
		<div class="row">
	
		 <div class="large-6 columns">
			  <label>Bank Account
			    <select name="bank">
					@foreach($accounts as $account)
						@if($account->accountType == 'b')
							@if($trans->creditAccountID == $account->id)
								<option value="{{ $account->id }}" selected>{{ $account->name }}</option>
							@else
								<option value="{{ $account->id }}">{{ $account->name }}</option>
							@endif
						@endif
					@endforeach
					@if($trans->creditAccountID == 0)
						<option value="cash" selected>Cash</option>
					@else
						<option value="cash">Cash</option>
					@endif
			    </select>
			  </label>
		 </div>
		    <div class="large-6 columns">
			  <label>Credit Account
			    <select name="payment">
					@foreach($accounts as $account)
						@if($trans->debitAccountID == $account->id)
							<option value="{{ $account->id }}" selected>{{ $account->name }}</option>
						@else
							<option value="{{ $account->id }}">{{ $account->name }}</option>
						@endif
					@endforeach
					@if($trans->debitAccountID == 0)
						<option value="cash" selected>Cash</option>
					@else
						<option value="cash">Cash</option>
					@endif
			    </select>
			  </label>
			</div>
		</div>
		<div class="row">
		    <div class="large-12 columns" style="padding-top: 15px;">
		      <label>Note
		        <textarea placeholder="Details..." name="note" >{!! $trans->note !!}</textarea>
		      </label>
		    </div>
	    </div>
	    <div class="row">
	    	<div class="large-6 columns" style="padding-top: 20px;">
				<input type="submit" class="button" value="Update">
				<a href="/edit/payment/{!! $type !!}/{!! $id!!}/delete" class="button delete" id="delete">Delete</a>
	    	</div>
	    </div>
		</form>
		</div>
</div>
<script>
	$(function() {
	    $('#delete').click(function() {
	        return window.confirm("Are you sure?");
	    });
	});
</script>
@endsection