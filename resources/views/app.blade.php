<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <link href="/css/custom.css" rel="stylesheet" type="text/css">
    <link href="/css/foundation.css" rel="stylesheet" type="text/css">
    <link href="/css/sortable-theme-light.css" rel="stylesheet" type="text/css">
    <link href="/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="/css/foundation-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="/css/pizza.css" rel="stylesheet" type="text/css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" type="text/css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet" type="text/css"><!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/zf-5.5.2/jq-2.1.4,pdfmake-0.1.18,dt-1.10.8,b-1.0.1,b-html5-1.0.1,b-print-1.0.1,cr-1.2.0,fh-3.0.0,r-1.0.7/datatables.min.css"/>

    <script src="/js/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="/js/snap.svg-min.js" type="text/javascript"></script>
    <script src="/js/pizza.js" type="text/javascript"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/r/zf-5.5.2/jq-2.1.4,pdfmake-0.1.18,dt-1.10.8,b-1.0.1,b-html5-1.0.1,b-print-1.0.1,cr-1.2.0,fh-3.0.0,r-1.0.7/datatables.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <nav class="top-bar" data-topbar>
		<ul class="title-area">
          <li class="name">
            <h1>
              <a href="/home">
                Money Manager
              </a>
            </h1>
          </li>
        </ul>
		 <section class="top-bar-section" style="float:right;">
 			<ul>
	            @if (Auth::guest())
					<li class="divider"></li>
					<li><a href="/login" class="top-button-logout">Login</a></li>
					<li class="divider"></li>
					<li><a href="/register" class="top-button-logout">Register</a></li>
				@else
				<li class="divider"></li>
					<li><a href="/options" class="top-buttons" style="padding-left: 40px;padding-right: 40px;">Options</a></li>
					<li class="divider"></li>
					<li><a href="/logout" class="top-button-logout">Logout</a></li>
				@endif
 			</ul>
		 </section>
	</nav>

	@yield('content')

    <div class="zurb-footer-bottom">
        <div class="row">
            <div class="medium-4 medium-4 push-8 columns">

            </div>

            <div class="medium-8 medium-8 pull-4 columns">


                <p class="copyright">© Matt Huddleston</p>
            </div>
        </div>
    </div><!-- Scripts -->
    <script src="/js/foundation.min.js" type="text/javascript"></script>
    <script src="/js/foundation-datepicker.min.js" type="text/javascript"></script>
    <script src="/js/fastclick.js" type="text/javascript"></script>
    <script src="/js/modernizr.js" type="text/javascript"></script>
    <script type="text/javascript">
		$(document).foundation();
	    $('.ddatepicker').fdatepicker();
	</script>

</body>
</html>
