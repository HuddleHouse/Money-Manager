@extends('app')

@section('title')
My Money
@endsection
@section('content')


  <div class="row">
  	<div class="small-12 columns">
	  	<center><h1>No data for selected month.</h1></center>
	  	@if(Session::has('message'))
  			<div class="alert alert-info" style="color: #008CBA; padding-bottom: 15px;"> {{Session::get('message')}} </div>
  		@endif
  	</div>
  </div>
<div class="row">
 	<div class="large-12 columns">
 	<center><h3>View Previous Months</h3></center> 	
 	</div>
</div>
<form class="form-horizontal" id="transForm" role="form" method="POST" action="/view">
<input type="hidden" name="_token" value="{!! csrf_token() !!}">
<div class="row">
 	<div class="large-6 large-offset-3 columns">
	 	<select name="month" id="month" onchange="" size="1">
		    <option value="Jan">January</option>
		    <option value="Feb">February</option>
		    <option value="Mar">March</option>
		    <option value="Apr">April</option>
		    <option value="May">May</option>
		    <option value="Jun">June</option>
		    <option value="Jul">July</option>
		    <option value="Aug">August</option>
		    <option value="Sep">September</option>
		    <option value="Oct">October</option>
		    <option value="Nov">November</option>
		    <option value="Dec">December</option>
		</select>
 	</div>
</div>
<div class="row">
 	<div class="large-6 large-offset-3 columns">
	 	<select name="year" id="year" onchange="" size="1">
		    <option value="2015">2015</option>
		    <option value="2016">2016</option>
		    <option value="2017">2017</option>
		</select>
 	</div>
</div>
<div class="row">
	<div class="large-6 large-offset-3 columns" style="padding-top: 20px;">
		<input type="submit" class="button" value="View SPending">
	</div>
</div>
</form>

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
