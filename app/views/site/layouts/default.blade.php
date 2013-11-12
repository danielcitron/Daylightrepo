<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
			@section('title')
			Daylight
			@show
		</title>


		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
        {{ Basset::show('public.css') }}
        {{ HTML::style('assets/compiled/public/assets/css/less/master-1d9e692f5ab97232a45bfdad34ffd850.css') }}
        {{ HTML::style('assets/css/global.css') }}

		<!-- CSS
		================================================== -->
		{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js') }}
		{{ HTML::script('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js') }}

		{{ HTML::script('assets/js/jquery.jeditable.mini.js') }}  
		{{ HTML::script('assets/js/index.js') }}            

		<style>
		@section('styles')
		@show
		</style>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
		<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.ico') }}}">
	</head>

	<body>
		<div id="wrap">
		<!-- Sidebar -->
		@if (Auth::check())
		<div class="sidebar">
			<div class="sidebar-inner">
				<div class="sidebar-header">
					<a href="/"><h1 class="title">Daylight</h1></a>
					<a href="/user"><h1 class="username">{{{ Auth::user()->username }}}</h1></a>
				</div>
				<div class="section boards-section">
					<a href="{{{ URL::to('/user/'.(Auth::user()->username).'/profile') }}}"><div class="section-header boards-header">
						<h1>BOARDS</h1>
					</div></a>
					<ul class="section-list boards-list">
						@foreach ((Boards::orderby('created_at', 'DESC')->where('user_id', '=', Auth::user()->id)->get()) as $authBoard)
							<li class="subheader board-subheader">
								<a href="{{'/boards/'.$authBoard->id}}"><h1 class="subheader-text">{{$authBoard->title}}</h1></a>
							</li>
						@endforeach
					</ul>
				</div>
				<div class="section subscriptions-section">
					<a href="{{{ URL::to('/user/'.(Auth::user()->username).'/profile') }}}"><div class="section-header subscriptions-header">
						<h1>SUBSCRIPTIONS</h1>
					</div></a>
					<ul class="section-list subscriptions-list">
						@foreach (Auth::user()->relationships as $authSubscription)
							<li class="subheader subscription-subheader">
								<a href="{{'/boards/'.$authSubscription->boards->id}}" style="display: inline-block;"><h1 class="subheader-text">{{$authSubscription->boards->title}}</h1></a>
								<a class="subheader-author" style="display: none;" href="{{'/user/'.$authSubscription->boards->user->username.'/profile'}}"><span>{{$authSubscription->boards->user->username}}</span></a>
							</li>
						@endforeach
					</ul>
				</div>
				<div class="section discover-section">
					<div class="discover-header-container"
						<a><div class="section-header discover-header">
							<h1>DISCOVER</h1>
						</div></a>
							<div class="discover-list-section discover-tab-container">
								<div class="discover-tab discover-recent-tab selected">Most Recent</div>
								<div class="discover-tab discover-random-tab">Random</div>
							</div>
					</div>

					<ul class="section-list discover-list">
						
						<div class="discover-list-section discover-recent-content">
							@foreach (Boards::orderby('created_at', 'DESC')->where('user_id', '!=', Auth::user()->id)->take(6)->get() as $recentBoard)
								<li class="subheader discover-subheader">
									<a href="{{'/boards/'.$recentBoard->id}}"><h1 class="subheader-text">{{$recentBoard->title}}</h1></a>
								</li>
							@endforeach
						</div>
						<div class="discover-random-content discover-list-section">
							@foreach (Boards::orderby(DB::raw('RAND()'))->where('user_id', '!=', Auth::user()->id)->take(6)->get() as $randomBoard)
								<li class="subheader discover-subheader">
									<a href="{{'/boards/'.$randomBoard->id}}"><h1 class="subheader-text">{{$randomBoard->title}}</h1></a>
								</li>
							@endforeach
						</div>
					</ul>
				</div>
				<div class="logout-container"><a class="logout" href="{{{ URL::to('/user/logout') }}}">Logout</a></div>
			</div>
		</div>
		@endif

		<!-- Container -->
		<div class="container">
			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
		</div>
		<!-- ./ container -->

		<!-- the following div is needed to make a sticky footer -->
		<div id="push"></div>
		</div>
		<!-- ./wrap -->


	    <div id="footer" style="display: none;">
	      <div class="container">
	      </div>
	    </div>

	</body>
</html>
