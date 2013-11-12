@extends('site.layouts.default')

@section('content')
<form class="form-horizontal" method="post" action="{{ URL::to('board/create') }}" autocomplete="off" enctype="multipart/form-data">

	<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<input class="form-control" type="text" name="title" id="title"  />
	<textarea placeholder="Describe your board in a few sentences" name="description" id="description" class="span5"></textarea>
	<div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" placeholder="Choose a photo to upload" name="cover_photo" id="cover_photo" value="{{{ Input::old('photo_location', isset($post) ? $post->photo_location : null) }}}" />
	</div>
	<!-- ./ image upload tab -->	
		<div class="form-group">
			<div class="col-md-12">
				<element class="btn-cancel close_popup">Cancel</element>
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="submit" class="btn btn-success">Create</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop