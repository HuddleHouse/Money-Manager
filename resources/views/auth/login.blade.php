@extends('app')

@section('content')
<div class="large-4 medium-6 small-12 small-centered columns" style="padding-bottom: 25px;">

  <div class="login-box">
@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
  <div class="row">
  <div class="large-12 columns">
<form class="form-horizontal" role="form" method="POST" action="/login">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
       <div class="row">
         <div class="large-12 columns">
             <input type="email" class="form-control" name="email" placeholder="Email" value="mthuddleston@gmail.com">
         </div>
       </div>
      <div class="row">
         <div class="large-12 columns">
             <input type="password" class="form-control" placeholder="Password" name="password">
         </div>
      </div>
      <div class="row">
	      <div class="large-12 columns">
			<label>
				<input type="checkbox" name="remember"> Remember Me
			</label>      
	      </div>
      </div>
      <div class="row">
        <div class="large-12 large-centered columns">
          <input type="submit" class="button expand" value="Log In"/>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
</div>
@endsection
