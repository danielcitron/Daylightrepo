@extends('site.layouts.default')


{{-- Web site Title --}}
@section('title')
{{{ String::title($block->title) }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
<a class="back-to-board" href="{{{'/boards/'.$block->boards->id}}}">Back to {{$block->boards->title}}</a>
<div class="expand-block-width"><span class="glyphicon glyphicon-zoom-in"></span></div>
<div class="block-comment-container">
	@if($block->user_id == Auth::user()->id)
		<form class="delete-form" method="post" action="{{{ URL::to($block->title.'/delete') }}}" autocomplete="off" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
			
			<!-- CSRF Token -->
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
			<!-- ./ csrf token -->
			
			<input type="hidden" name="block_id" value="{{{ $block->id }}}" />
			<input type="hidden" name="board_id" value="{{{ $block->boards->id }}}" />

			<button type="submit" class="btn btn-danger">Delete</button>
		</form>
	@endif

	@if($block->user_id == Auth::user()->id)
		<h1 class="block-title edit-block-title" data-id="{{$block->id}}">{{ $block->title }}</h3>
	@else
		<h3 class="block-title" data-id="{{$block->id}}">{{ $block->title }}</h3>
	@endif
	<a href="{{'/user/'.$block->author->username.'/profile'}}"><div class="block-author">{{$block->author->username}}</div></a>
	

	@if($block->photos == 1)
		<div class="post-img-container">
			@if($block->user_id == Auth::user()->id)
				<form class="edit-img-form" method="post" action="{{{ URL::to('/edit/block/photo') }}}" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />
					
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" /> 
					<!-- ./ csrf token -->
					
					<input type="hidden" name="block_title" value="{{{$block->title}}}" />
					<input type="hidden" name="block_id" value="{{{$block->id}}}" />
					<input type="file" data-validation="required" data-validation-error-msg="Please select a photo." placeholder="Choose a photo to upload" name="block_photo" id="block_photo" />
					<button type="submit" class="btn btn-success">Update Photo</button>
				</form>
			@endif
			<img class="post-img" src="{{{ $block->photo_location }}}" alt="{{{ $block->file_name }}}">
		</div>
		<div class="block-details-container">
			@if($block->user_id == Auth::user()->id)
				<p class="block-text block-text-photo edit-block-text" data-id="{{$block->id}}">{{ $block->content() }}</p>
			@else
				<p class="block-text block-text-photo" data-id="{{$block->id}}">{{ $block->content() }}</p>
			@endif
		</div>
	@else
		<div class="block-details-container">
			@if($block->user_id == Auth::user()->id)
				<p class="block-text block-text-primary edit-block-text" data-id="{{$block->id}}">{{ $block->content() }}</p>
			@else
				<p class="block-text block-text-primary" data-id="{{$block->id}}">{{ $block->content() }}</p>
			@endif
		</div>
	@endif

	<hr />

	<a id="comments"></a>
	<h4>{{{ $comments->count() }}} Comments</h4>

	<hr />
	<div class="block-comments-container">
	@if ($comments->count())
		@foreach ($comments as $comment)
			<div class="block-comment">
				@if($comment->photos == 1)
						<img class="comment-img" src="{{{ $comment->photo_location }}}" alt="{{{ $comment->file_name }}}"></img>
				@endif
				<div class="comment-text-container">
					<div class="comment-details">
						<a href={{{"/user/".$comment->author->username."/profile"}}} class="comment-username">{{{ $comment->author->username }}}</a>
						<div class="comment-time" style="display:none;">{{{ $comment->date() }}}</div>
					</div>
												
					<div class="comment-text">{{ $comment->content() }}</div>
					</div>
			</div>
			<hr />
		@endforeach
	@else
		<hr />
	@endif
	</div>

	@if ( ! Auth::check())
	You need to be logged in to add comments.<br /><br />
	Click <a href="{{{ URL::to('user/login') }}}">here</a> to login into your account.
	@elseif ( ! $canComment )
	You don't have the correct permissions to add comments.
	@else

	@if($errors->has())
	<div class="alert alert-danger alert-block">
	<ul>
	@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
	</ul>
	</div>
	@endif

	<h4>Add a Comment</h4>
	<form class="comment-add"  method="post" action="{{{ '/block/add/comment' }}}" autocomplete="off" enctype="multipart/form-data">
		<input type="hidden" name="_token" value="{{{ Session::getToken() }}}" />

		<textarea class="col-md-12 input-block-level" rows="4" name="comment" id="comment">{{{ Request::old('comment') }}}</textarea>
		<input type="hidden" name="block_id" value="{{{$block->id}}}" />
		<input type="file" placeholder="Choose a photo to upload" name="comment_photo" id="comment_photo" />
		<button type="submit" class="btn btn-success">Reply</button>
	</form>
	@endif
</div>
@stop
