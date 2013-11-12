@extends('site.layouts.default')

{{-- Content --}}
@section('content')
<div class="create-container"><a href="{{{ URL::to('admin/blogs/create') }}}" class=" new-button btn btn-small btn-info iframe"><span>+</span></a></div>
@foreach ($posts as $post)
<div class="block-outer text-block-outer"><a href="{{{ $post->url() }}}">
	<div class="block-inner">
		<!-- Post Title -->
		<div class="block-title-container">
				<h4><strong><a href="{{{ $post->url() }}}">{{ String::title($post->title) }}</a></strong></h4>
		</div>
		<!-- ./ post title -->

		<!-- Post Content -->
			<!-- image post check if image then display image -->
			@if ($post->photos === 1)
				<div class="photo-block-img">
					<a href="{{{ $post->url() }}}" class="thumbnail"><img src="{{{ $post->photo_location }}}" alt="{{{ $post->file_name }}}"></a>
				</div>
				<div class="photo-text-content-container">
					<p><a href="{{{ $post->url() }}}">
						{{ String::tidy(Str::limit($post->content, 200)) }}
					</a></p>
				</div>
			<!-- ./imagepost !-->
			@else
				<div class="text-content-container">
					<p><a href="{{{ $post->url() }}}">
						{{ String::tidy(Str::limit($post->content, 200)) }}
					</a></p>
				</div>
			@endif
		<!-- ./ post content -->

		<!-- Post Footer -->
		<div class="block-footer">
				<p></p>
				<p>
					<span class="glyphicon glyphicon-user"></span> by <span class="muted">{{{ $post->author->username }}}</span>
					| <span class="glyphicon glyphicon-calendar" style="display: none;"></span> <!--Sept 16th, 2012-->{{{ $post->date() }}}
					| <span class="glyphicon glyphicon-comment"></span> <a href="{{{ $post->url() }}}#comments">{{$post->comments()->count()}} {{ \Illuminate\Support\Pluralizer::plural('Comment', $post->comments()->count()) }}</a>
				</p>
		</div>
		<!-- ./ post footer -->
	</div>
</a></div>

@endforeach


<!-- hidden new submissions !-->



<!-- ./hidden !-->

{{ $posts->links() }}

@stop
