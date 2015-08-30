@extends('app')

@section('title')
My Money
@endsection
@section('content')
<div class="row" style="margin-top: 50px;">
	<div class="large-10 large-offset-1 columns">
	<form class="form-horizontal" id="transForm" role="form" method="POST" action="/edit/{!! $id !!}">
	<input type="hidden" name="_token" value="{!! csrf_token() !!}">
	<input type="radio" name="form" value="payment" checked="true" style="display: none;">
		<div class="row">
			<div class="large-6 columns">
		      <label>Date
		        <input type="datetime" class="ddatepicker" value="{!! $transaction->date !!}" name="date" data-date-format="mm/dd/yyyy" id="dp">
		      </label>
		    </div>
		    <div class="large-6 columns">
		      <label>Amount
		        <input type="number" step="any" name="amount" placeholder="$5.00" value="{!! $transaction->amount !!}"/>
		      </label>
		    </div>
		</div>
	
		<div class="row">
	
		    <div class="large-6 columns" style="margin-bottom: 15px;">
			  <label>Category
			    <select name="type">
					@foreach($types as $type)
						@if($transaction->typeID == $type->id)
							<option value="{{ $type->id }}" selected>{{ $type->name }}</option>
						@else
							<option value="{{ $type->id }}">{{ $type->name }}</option>
						@endif
					@endforeach
			    </select>
			  </label>
			</div>
	
		    <div class="large-6 columns">
			  <label>Credit Account
			    <select name="payment">
					@foreach($accounts as $account)
						@if($transaction->accountID == $account->id)
							<option value="{{ $account->id }}" selected>{{ $account->name }}</option>
						@else
							<option value="{{ $account->id }}">{{ $account->name }}</option>
						@endif
					@endforeach
					@if($transaction->accountID == NULL)
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
		        <textarea placeholder="Details..." name="note" >{!! $transaction->note !!}</textarea>
		      </label>
		    </div>
	    </div>
	    <div class="row">
	    	<div class="large-6 columns" style="padding-top: 20px;">
				<input type="submit" class="button" value="Update">
				<a href="/edit/{!! $id!!}/delete" class="button delete" id="delete">Delete</a>
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