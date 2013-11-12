@extends('site.layouts.default')

@section('content')
<div class="create-container"><a href="{{{ URL::to('create/board') }}}" class=" new-button btn btn-small btn-info iframe"><span>+</span></a></div>
<div class="board-header">
	<h1>{{ String::tidy(Str::limit($user->username, 200)) }} </h1>
</div>
<ul class="board-list">
@foreach ($boards as $board)
	@if($board->user_id === $user->id)	
		
		<li class="board-container">
			<div class="board-text-container">
				<h1 class="board-title">
					{{ String::tidy(Str::limit($board->title, 200)) }}
				</h1>
				<p class="board-description"><a href="{{{ $board->title }}}">
					{{ String::tidy(Str::limit($board->description, 200)) }}
				</a></p>
				<span class="glyphicon glyphicon-user"></span> by <span class="muted">{{{ $user->username }}}</span>
				<span class="glyphicon glyphicon-eye-open"></span> <span class="muted">{{$userBoard->relationships()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Subscriber', $userBoard->relationships()->count()) }}</span>
				<form  method="post" action="{{{ URL::to('follow') }}}" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
					
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
					<!-- ./ csrf token -->
					
					<input type="hidden" name="board_title" value="{{{ String::tidy(Str::limit($board->title, 200)) }}}" />

					<input type="hidden" name="board_id" value="{{{ $board->id }}}" />

					<button type="submit" class="btn btn-success">Subscribe</button>
				</form> 

			</div>		

			<img class="board-cover" src="{{{ $board->cover_location }}}" alt="{{{ $board->file_name }}}"></img>
		</li>
		
	@endif
@endforeach
</ul>
@stop