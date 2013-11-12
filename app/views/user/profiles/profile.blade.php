@extends('site.layouts.default')


@section('content')

<div class="profile-header">
	<h1>{{ String::tidy(Str::limit($userProfile->username, 200)) }} </h1>
	<div class="header-count-container boards">
		<span class="boards-count number">{{$userBoards->count()}}</span><span class="boards-count count-label"> {{ \Illuminate\Support\Pluralizer::plural('Board', $userBoards->count()) }}</span>
	</div>
	<div class="header-count-container blocks">
		<span class="blocks-count number">{{$userBlocks->count()}}</span><span class="blocks-count count-label"> {{ \Illuminate\Support\Pluralizer::plural('Blocks', $userBlocks->count()) }}</span>
	</div>
</div>
<div class="profile-subheader">
	<div class="subheader-container">
		<span class="profile-boards-menu selected">Boards</span>
		<span class="profile-blocks-menu">Blocks</span>
		<span class="profile-subscriptions-menu">Subscriptions</span>
	</div>
</div>

<ul class="board-list boards-initial-list">
	@foreach ($userBoards as $userBoard)
			<li class="board-container">
				<div class="board-text-container">
					<a href="{{{ '/boards/'.$userBoard->id }}}"><h1 class="board-title">
						{{ String::tidy(Str::limit($userBoard->title, 200)) }}
					</h1></a>
					<p class="board-description"><a href="{{{ '/boards/'.$userBoard->id }}}">
						{{ String::tidy(Str::limit($userBoard->description, 200)) }}
					</a></p>
					<a href="{{{ '/user/'.$userProfile->username.'/profile'}}}">
					<span class="glyphicon glyphicon-user"></span> <span class="muted">{{{ $userProfile->username }}}</span>
					</a>
					<a href="{{{ '/boards/'.$userBoard->id }}}"><span class="glyphicon glyphicon-eye-open"></span> <span class="muted">{{$userBoard->relationships()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Subscriber', $userBoard->relationships()->count()) }}</span></a>
					@if ($userBoard->user_id !== Auth::user()->id) 
						@if ($userBoard->relationships()->where('user_id', '=', Auth::user()->id)->first())
							<form  method="post" action="{{{ URL::to('unsubscribe') }}}" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
							
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
							<!-- ./ csrf token -->
							
							<input type="hidden" name="board_title" value="{{{ $userBoard->title }}}" />

							<input type="hidden" name="board_id" value="{{{ $userBoard->id }}}" />

							<button type="submit" class="btn btn-danger">Unsubscribe</button>
							</form>
						@else
						<form  method="post" action="{{{ URL::to('follow') }}}" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
							
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
							<!-- ./ csrf token -->
							
							<input type="hidden" name="board_title" value="{{{ $userBoard->title }}}" />

							<input type="hidden" name="board_id" value="{{{ $userBoard->id }}}" />

							<button type="submit" class="btn btn-success">Subscribe</button>
						</form>
						@endif 
					@endif
					@if ($userBoard->user_id == Auth::user()->id)
						<form class="delete-form" method="post" action="{{{ URL::to('/boards/'.$userBoard->title.'/delete') }}}" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
							
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
							<!-- ./ csrf token -->

							<input type="hidden" name="board_id" value="{{{ $userBoard->id }}}" />

							<button type="submit" class="btn btn-danger">Delete</button>
						</form>
					@endif
				</div>

				<img class="board-cover" src="{{{ $userBoard->cover_location }}}" alt="{{{ $userBoard->file_name }}}"></img>
			</li>
	@endforeach
	@if($userProfile->id == Auth::user()->id)
	<div class="create-container"><a href="{{{ URL::to('/create/board') }}}" class=" new-button btn btn-small btn-info iframe"><span>+</span></a></div>
	@endif
</ul>

<ul class="block-list" style="display:none;">

	@foreach($userBlocks as $userBlock)
			
			<li class="block-container">
				
				<div class="block-inner">
					<form class="delete-form" method="post" action="{{{ URL::to($userBlock->title.'/delete') }}}" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
						
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
						<!-- ./ csrf token -->
						
						<input type="hidden" name="block_id" value="{{{ $userBlock->id }}}" />


						<button type="submit" class="btn btn-danger">Delete</button>
					</form> 
					@if($userBlock->photos == 1)
						<div class="photo-block-container">
							<a href="{{{ '/block/'.$userBlock->id }}}"> <img class="post-img" src="{{{ $userBlock->photo_location }}}" alt="{{{ $userBlock->file_name }}}"></a>
							<p><a href="{{{ '/block/'.$userBlock->id }}}">
									{{ String::tidy(Str::limit($userBlock->content, 200)) }}
							</a></p>
							<a class="text-background" href="{{{ '/block/'.$userBlock->id }}}">
							</a>
						</div>
					@else
						<div class="text-block-container">

							<div class="text-container">
								<a href="{{{ '/block/'.$userBlock->id }}}"><p>
									{{ String::tidy(Str::limit($userBlock->content, 200)) }}
								</p></a>
							</div>

						</div>

					@endif
				</div>
				<div class="block-footer">
					<p>
						<span class="glyphicon glyphicon-user"></span><span class="muted"> {{{ $userBlock->author->username }}}</span>
						| <span class="glyphicon glyphicon-comment"></span> <a href="{{{ '/block/'.$userBlock->id }}}#comments">{{$userBlock->comments()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Comment', $userBlock->comments()->count()) }}</a>
					</p>
				</div>
				<a class="block-bg" href="{{{ '/block/'.$userBlock->id }}}"><div></div></a>

			</li>
		@endforeach
</ul>

<ul class="subscription-list board-list" style="display:none;">
	@foreach ($userSubscriptions as $userSubscription)
			
			<li class="board-container">
				<div class="board-text-container">
					<a href="{{{ '/boards/'.$userSubscription->boards->id }}}"><h1 class="board-title">
						{{ String::tidy(Str::limit($userSubscription->boards->title, 200)) }}
					</h1></a>
					<p class="board-description"><a href="{{{ '/boards/'.$userSubscription->boards->id }}}">
						{{ String::tidy(Str::limit($userSubscription->boards->description, 200)) }}
					</a></p>
					<a href="{{'/user/'.$userSubscription->boards->user->username.'/profile'}}"><span class="glyphicon glyphicon-user"></span> <span>{{{ $userSubscription->boards->user->username }}}</span></a>
					<a href="{{{ '/boards/'.$userSubscription->boards->id }}}"><span class="glyphicon glyphicon-eye-open"></span> <span>{{$userSubscription->boards->relationships()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Subscriber', $userSubscription->boards->relationships()->count()) }}</span></a>
					@if ($userSubscription->boards->user_id !== Auth::user()->id) 
						@if ($userSubscription->boards->relationships()->where('user_id', '=', Auth::user()->id)->first())
							<form  method="post" action="{{{ URL::to('unsubscribe') }}}" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
							
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
							<!-- ./ csrf token -->
							
							<input type="hidden" name="board_title" value="{{{ $userSubscription->boards->title }}}" />

							<input type="hidden" name="board_id" value="{{{ $userSubscription->boards->id }}}" />

							<button type="submit" class="btn btn-danger">Unsubscribe</button>
							</form>
						@else
						<form  method="post" action="{{{ URL::to('follow') }}}" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
							
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
							<!-- ./ csrf token -->
							
							<input type="hidden" name="board_title" value="{{{ $userSubscription->boards->title }}}" />

							<input type="hidden" name="board_id" value="{{{ $userSubscription->boards->id }}}" />

							<button type="submit" class="btn btn-success">Subscribe</button>
						</form>
						@endif
					@endif 

				</div>		

				<img class="board-cover" src="{{{ $userSubscription->boards->cover_location }}}" alt="{{{ $userSubscription->boards->file_name }}}"></img>
			</li>
	@endforeach
</ul>

@stop