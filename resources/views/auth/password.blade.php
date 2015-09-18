@extends('app')

@section('content')
<div class="container-fluid">
	
	<div class="large-4 medium-6 small-12 small-centered columns" style="padding-bottom: 25px;">
		<div class="login-box">
			<div class="panel-heading">Reset Password</div>
				<div class="panel-body">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

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

					<form class="form-horizontal" role="form" method="POST" action="/password/email">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<label class="control-label">E-Mail Address</label>
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							
						<div class="form-group">
								<button type="submit" class="button expand">
									Send Password Reset Link
								</button>
						</div>
					</form>
				</div>
		</div>
	</div>
</div>
@endsection
