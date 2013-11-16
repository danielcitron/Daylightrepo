@extends('site.layouts.default')

@section('title')
{{ String::tidy(Str::limit($board->title, 200)) }} ::
@parent
@stop

@section('content')
<div class="view-board">
	<div class="header-container">
		<div class="cover">
			@if($board->user_id == Auth::user()->id)
				<h1 class="board-title edit-board-title">{{ $board->title }}</h1>
				<div class="author-container">
					<a href="{{'/user/'.$board->user->username.'/profile'}}" class="board-author">{{ $board->user->username }}</a>
				</div>
			@else
				<h1 class="board-title">{{ $board->title }}</h1>
				<div class="author-container">
					<a href="{{'/user/'.$board->user->username.'/profile'}}" class="board-author">{{ $board->user->username }}</a>
				</div>
			@endif
			<div class="cover-details">
				<span class="glyphicon glyphicon-th-large"></span> <span class="muted">{{$board->posts()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Block', $board->posts()->count()) }}</span>
				</br>
				<span class="glyphicon glyphicon-eye-open"></span> <span class="muted">{{$board->relationships()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Subscriber', $board->relationships()->count()) }}</span>
			</div>
			@if($board->user_id == Auth::user()->id)	
				<form class="edit-cover-form" method="post" action="{{{ URL::to('edit/board/cover') }}}" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
					
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
					<!-- ./ csrf token -->
					
					<input type="hidden" name="board_title" value="{{{$board->title}}}" />

					<input type="hidden" name="board_id" value="{{{ $board->id }}}" />
					<input type="file" placeholder="Choose a photo to upload" name="cover_photo" data-validation="required" id="cover_photo" />
					<button type="submit" class="btn btn-success">Update Cover</button>
				</form>
			@endif
			@if ($board->user_id !== Auth::user()->id)  
				@if($subscribed)
					<div class="subscribed-container">
						<div class="subscribed">
							<span class="glyphicon glyphicon-ok-sign"></span><span> Subscribed</span>
						</div>
						<form style="position: absolute;" class="unsubscribe-form" method="post" action="{{{ URL::to('unsubscribe') }}}" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
							
							<!-- CSRF Token -->
							<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
							<!-- ./ csrf token -->
							
							<input type="hidden" name="board_title" value="{{{$board->title}}}" />

							<input type="hidden" name="board_id" value="{{{ $board->id }}}" />

							<button type="submit" class="btn btn-danger">Unsubscribe</button>
						</form>
					</div>
				@else

					<form class="subscribe-form" method="post" action="{{{ URL::to('follow') }}}" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
						
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
						<!-- ./ csrf token -->
						
						<input type="hidden" name="board_title" value="{{{$board->title}}}" />

						<input type="hidden" name="board_id" value="{{{ $board->id }}}" />

						<button type="submit" class="btn btn-success">Subscribe</button>
					</form>
				@endif 
			@endif
			<img class="board-cover" src="{{{ $board->cover_location }}}" alt="{{{ $board->file_name }}}"></img>
		</div>
		@if($board->user_id == Auth::user()->id)
			<div class="board-description edit-board-description" data-id="{{$board->id}}">{{ String::tidy(Str::limit($board->description, 200)) }}</div>
			@else
			<div class="board-description" data-id="{{$board->id}}">{{ String::tidy(Str::limit($board->description, 200)) }}</div>
		@endif
	</div>
	<div style="display:none">{{$i=1}}</div>
	@if($board->user_id == Auth::user()->id)
	<ul class="board-blocks board-blocks-draggable" data-title="{{ String::tidy(Str::limit($board->title, 200)) }}">
	@else
	<ul class="board-blocks" data-title="{{ String::tidy(Str::limit($board->title, 200)) }}">
	@endif
		@foreach($blocks as $block)
			
			<li class="block-container" data-id="{{$i}}">
				
				<div id="i-incrementor" style="display:none">{{$i++}}</div>
				@if($board->user_id == Auth::user()->id)
					<form class="block-delete-form-board" method="post" action="{{{ URL::to($block->title.'/delete') }}}" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
						
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
						<!-- ./ csrf token -->
						
						<input type="hidden" name="block_id" value="{{{ $block->id }}}" />

						<input type="hidden" name="board_id" value="{{{ $board->id }}}" />

						<button type="submit" class="btn btn-danger">Delete</button>
					</form>
				@endif 
				<div class="block-inner">
					
					@if($block->photos == 1)
						<a href="{{{ '/block/'.$block->id }}}"> <img class="post-img" src="{{{ $block->photo_location }}}" alt="{{{ $block->file_name }}}"></a>
					@else
						<div class="block-text-container">
							<p><a href="{{{ '/block/'.$block->id }}}">
								{{ String::tidy(Str::limit($block->content, 200)) }}
							</a></p>
						</div>
					@endif
				</div>
				<div class="block-footer">
					<p>
						<span class="glyphicon glyphicon-user"></span> by <span class="muted">{{{ $block->author->username }}}</span>
						| <span class="glyphicon glyphicon-calendar" style="display: none;"></span> <!--Sept 16th, 2012-->{{{ $block->date() }}}
						| <span class="glyphicon glyphicon-comment"></span> <a href="{{{ '/block/'.$block->id }}}#comments">{{$block->comments()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Comment', $block->comments()->count()) }}</a>
					</p>
				</div>

			</li>
		@endforeach
	</ul>
	@if ( ! Auth::check())
	<div>You need to be logged in to add blocks.<br/><br/>
	Click <a href="{{{ URL::to('user/login') }}}">here</a> to login into your account.</div>
	@else
	<div class="create-new-container">
		<div class="create-new-block">
			<div class="add-container">+</div>
		</div>
		<div class="form-container">
			<form  method="post" action="{{{ URL::to('board/' . $board->title . '/block/create') }}}" autocomplete="off" enctype="multipart/form-data">
				<div class="form-tabs">
					<div class="form-tab tab-text selected">
						T
					</div>
					<div class="form-tab tab-photos">
						<span class="glyphicon glyphicon-picture"></span>
					</div>
				</div>
				<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />

				<!-- CSRF Token -->
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<!-- ./ csrf token -->
	            
	            <div class="form-field-container form-title-container">
					<input class="block-form-title" placeholder="Block Title" type="text" name="title" id="title" data-validation="required" />
				</div>
				<div class="form-field-container form-photo-container hidden">
		            <label for="photo">Photo</label>
		        	</br>
	           		<input class="photo-input" type="file" placeholder="Choose a photo to upload" name="block_photo" id="block_photo" data-validation="" />
	        	</div>
	        	<div class="form-field-container add-description hidden">Add Description</div>
				<div class="form-field-container form-content-container">
					<textarea placeholder="Block Content" class="block-form-content" data-validation="required" name="content" value="content"></textarea>
				</div>

	            
				<div>
						<button type="submit" class="create-button">Create</button>
				</div>
			</form>
		</div>
	</div>
	@endif
</div>

@stop

{{-- Scripts --}}
@section('scripts')

@stop