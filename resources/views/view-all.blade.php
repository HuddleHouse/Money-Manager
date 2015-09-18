@extends('app')

@section('title')
My Money
@endsection
@section('content')


  <div class="row">
  	<div class="small-12 columns">
	  	<center><h1>All of your spending (add total spent each month)</h1></center>
	  	@if(Session::has('message'))
  			<div class="alert alert-info" style="color: #008CBA; padding-bottom: 15px;"> {{Session::get('message')}} </div>
  		@endif
  	</div>
  </div>

@foreach($spending as $spent)
	<p>Month: {!! $spent['month'] !!}, {!! $spent['year'] !!}</p>
	<p>
	@foreach($spent['trans'] as $trans)
	{!! $trans['name'] !!}: {!! $trans['amount'] !!}<br>
	@endforeach
	</p>
@endforeach

<script type="text/javascript">

</script>
@endsection
