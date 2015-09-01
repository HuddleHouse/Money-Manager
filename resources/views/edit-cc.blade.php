@extends('app')

@section('title')
My Money
@endsection
@section('content')
<div class="row" style="margin-top: 50px;">
	<center><h1>Edit Credit Card Information</h1></center>
	<div class="large-10 large-offset-1 columns">
	<form class="form-horizontal" id="transForm" role="form" method="POST" action="/edit/cc/{!! $id !!}">
	<input type="hidden" name="_token" value="{!! csrf_token() !!}">
	<div class="row">
		<div class="large-12 columns">
	      <label>Name
	        <input type="text" name="name" value="{!! $cc->name !!}"/>
	      </label>
	    </div>
	</div>

	<div class="row">
		<div class="large-6 columns">
	      <label>Credit Limit (No commas)
	        <input type="text" name="limit" value="{!! $cc->creditLimit !!}"/>
	      </label>
	    </div>
	    <div class="large-6 columns">
	      <label>Statement Date
	        <input type="text"  placeholder="Day of monthly statement." name="date" value="{!! $cc->statementDay !!}">
	      </label>
	    </div>

	</div>	
		<div class="row">
	    	<div class="large-6 columns" style="padding-top: 20px;">
				<input type="submit" class="button" value="Update">
				<a href="/edit/cc/{!! $id!!}/delete" class="button delete" id="delete">Delete</a>
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