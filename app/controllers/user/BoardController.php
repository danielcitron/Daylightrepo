<?php

class BoardController extends AdminController {
	
	protected $board;
    protected $post;
    protected $user;
    protected $comment;

	public function __construct(Boards $board, Post $post, User $user, Comment $comment)
    {
        parent::__construct();
        $this->boards = $board;
        $this->post = $post;
        $this->user = $user;
        $this->comment = $comment;
    }

	public function getBoards()
    {
        
        $boards = DB::table('boards')->get();
        $user = Auth::user();

        return View::make('user/boards/index', array('boards' => $boards), array('user' => $user));
    }

    public function createBoard()
	{
		$input = Input::all();

		$rules = array(
			'title' => 'required|min:3',
			'description' => 'required|min:3',
            'cover_photo' => 'required'
		);

		if (Input::hasFile('cover_photo')) {
            //photo upload
            $extension = Input::file('cover_photo')->getClientOriginalExtension();
            $input = Input::file('cover_photo');
            $directory = public_path().'/uploads/'.(Auth::user()->username).'/covers/';
            $filename = Input::file('cover_photo')->getClientOriginalName();
            Input::file('cover_photo')->move($directory, $filename);
            $upload_success = Input::file('cover_photo', $directory, $filename);

            if( $upload_success ) {
                Session::flash('status_success', 'Successfully uploaded!');
            } else {
                Session::flash('status_error', 'An error occurred while uploading - please try again.');
            }
        }

		$validation = Validator::make(Input::all(), $rules);

		if ($validation->passes()) {
			$user = Auth::user();

			$this->boards->user_id = $user->id;
			$this->boards->title = Input::get('title');
			$this->boards->description = Input::get('description');

			if (Input::hasFile('cover_photo')) {
                if( $upload_success ) {

                    $this->boards->cover_photo  = 1;
                    $this->boards->file_name = $filename;
                    $this->boards->cover_location = 'http://daylight.cc/uploads/'.(Auth::user()->username).'/covers/'.$filename;
                }
            }


            if($this->boards->save())
            {
                // Redirect to the home page
                return Redirect::to('/');
            }
		}

        return Redirect::to('/')->with('error', 'Cover Photo Required. Please try again.');
	}

    public function getBlocks($id)
    {
        // Get this board data
        $board = $this->boards->where('id', '=', $id)->first();
        // Check if the board exists
        if (is_null($board))
        {
            return App::abort(404);
        }

        //get blocks
        $blockbytimes = $board->posts()->orderBy('created_at', 'ASC')->get();
        $i = 1;
        
        foreach ($blockbytimes as $blockbytime) {
            if($blockbytime->ordered === 1) {
                $i++;
            }
        }


        $user = Auth::user();
        $subscribed = $user->relationships()->where('boards_id', '=', $id)->first();
        $canPost = false;
        if ($user) {
            $canPost = true;
        }




        foreach ($blockbytimes as $blockbytime) {
            if($blockbytime->ordered === 0) {
                $blockbytime->block_order = $i;
                $blockbytime->order_original = $i;
                $i++;
                $blockbytime->ordered = 1;
                ($blockbytime->save());
            }
            else {
                $blockbytime->order_original = $blockbytime->block_order;
                ($blockbytime->save());
            }
        }

        $blocks = $board->posts()->orderBy('block_order', 'ASC')->get();

        


        return View::make('user/boards/view_board', compact('board', 'blocks', 'user', 'canPost', 'subscribed'));
    }

    public function createBlock($title)
    {
        // Get this board data
        $board = $this->boards->where('title', '=', $title)->first();

        // Check if the board exists
        if (is_null($board))
        {
            return App::abort(404);
        }

        $user = Auth::user();
        $input = Input::all();

        if (Input::hasFile('block_photo')) {
            //photo upload
            $extension = Input::file('block_photo')->getClientOriginalExtension();
            $input = Input::file('block_photo');
            $directory = public_path().'/uploads/'.(Auth::user()->username);
            $filename = Input::file('block_photo')->getClientOriginalName();
            Input::file('block_photo')->move($directory, $filename);
            $upload_success = Input::file('block_photo', $directory, $filename);
           // Image::createDimensions('http://localhost:8000/uploads/'.(Auth::user()->username).'/'.$filename);



            if( $upload_success ) {
                Session::flash('status_success', 'Successfully uploaded!');
                echo "CONFRIRM";
            } else {
                Session::flash('status_error', 'An error occurred while uploading - please try again.');
            }
        }
        
        $rules = array(
            'title'   => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes())
        {
            $user = Auth::user();

            

            $post = new Post;
            $post->title            = Input::get('title');
            $post->boards_id        = $board->id;
            $post->slug             = Str::slug(Input::get('title'));
            $post->content          = Input::get('content');
            $post->user_id          = $user->id;

            if (Input::hasFile('block_photo')) {
                if( $upload_success ) {

                    $post->photos  = 1;
                    $post->file_name = $filename;
                    $post->photo_location = 'http://daylight.cc/uploads/'.(Auth::user()->username).'/'.$filename;
                }
            }

            if($post->save())
            {
                return Redirect::to('/boards/'.$board->id);
            }

            
            return Redirect::to('/boards/'.$board->id)->with('error', Lang::get('Failed to Make Block'));
        }

        
        return Redirect::to('/boards/'.$board->id)->withInput()->withErrors($validator);
    }

    public function orderBlocks()
    {
       $title = Input::get('title');

       $board = $this->boards->where('title', '=', $title)->first();
       
       $boardTitle = $board->title;
       $boardId = $board->id;

      // $blocks = $board->posts()->orderBy('block_order', 'ASC')->get();
       $blocks = $board->posts()->orderBy('order_original', 'ASC')->get();

       $order = Input::get('item_ids');


       $i = 0;
        foreach ($order as $idx) {
            $blocks[$idx-1]->block_order = $i;
            $i++;
            ($blocks[$idx-1]->save());
        }


    }


    public function deleteBlock()
    {
        $blockid = Input::get('block_id');
        $boardid = Input::get('board_id');

        $board = $this->boards->where('id', '=', $boardid)->first();
        $block = $board->posts()->where('id', '=', $blockid)->first();

        $rules = array(
            'block_id' => 'required|integer',
            'board_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes())
        {

            $block->delete();

            $post = Post::find($blockid);
            if(empty($post))
            {
               
                return Redirect::to('/boards/'.$board->id);
            }
        }
        
        return Redirect::to('/boards/'.$board->id)->with('error', Lang::get('Failed to delete block'));
    }

    public function deleteBoard()
    {
        $boardid = Input::get('board_id');

        $board = $this->boards->where('id', '=', $boardid)->first();
        $relationships = $board->relationships();

        $rules = array(
            'board_id' => 'required|integer'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes())
        {

            $board->delete();
            $relationships->delete;

            $boarddeleted = Post::find($boardid);
            if(empty($boarddeleted))
            {
                return Redirect::to('/user/'.(Auth::user()->username).'/profile');
            }
        }
        // There was a problem deleting the board
        return Redirect::to('/user/'.(Auth::user()->username).'/profile')->with('error', Lang::get('There was a problem deleting the board'));
    }

    public function editTitle()
    {
       $origValue = Input::get('origValue');
       $board = $this->boards->where('title', '=', $origValue)->first();
       $newtitle = Input::get('newvalue');
       if($newtitle) {
            $board->title = $newtitle;

            if($board->save())
            {
                return $newtitle;
            }

       }
               
        return 'Error, refresh.';
    }

    public function editCover()
    {
       $boardTitle = Input::get('board_title');
       $board = $this->boards->where('title', '=', $boardTitle)->first();

       $extension = Input::file('cover_photo')->getClientOriginalExtension();
       $input = Input::file('cover_photo');
       $directory = public_path().'/uploads/'.(Auth::user()->username).'/covers/';
       $filename = Input::file('cover_photo')->getClientOriginalName();
       Input::file('cover_photo')->move($directory, $filename);
       $upload_success = Input::file('cover_photo', $directory, $filename);

       if (Input::hasFile('cover_photo')) {
            if( $upload_success ) {

                $board->cover_photo  = 1;
                $board->file_name = $filename;
                $board->cover_location = 'http://daylight.cc/uploads/'.(Auth::user()->username).'/covers/'.$filename;
            }
        }
            
       if($board->save())
            {
                return Redirect::to('/boards/'.$board->id);
            }
        return Redirect::to('/boards/'.$board->id)->with('error', Lang::get('Could not update Board Cover'));
    }

    public function editDescription()
    {
       $origValue = Input::get('origValue');
       $newvalue = Input::get('newvalue');
       $boardId = Input::get('boardId');

       $board = $this->boards->where('id', '=', $boardId)->first();
       
       $board->description = $newvalue;
       
       if($board->save())
            {
                return ($newvalue);
            }
        return 'Edit not saved, please refresh the page.';
    }

    public function getBlock($id)
    {
        $block = $this->post->where('id', '=', $id)->first();

        if (is_null($block))
        {
            return App::abort(404);
        }

        $comments = $block->comments()->orderBy('created_at', 'ASC')->get();

        $user = Auth::user();
        if($user) {
            $canComment = true;
        }

        // Show the page
        return View::make('site/blocks/view_post', compact('block', 'comments', 'canComment'));
    }

    public function editBlockTitle()
    {
       $origValue = Input::get('origValue');
       $newvalue = Input::get('newvalue');
       $blockId = Input::get('blockId');

       $block = $this->post->where('id', '=', $blockId)->first();

       if ($block) {
            $block->title = $newvalue;

            if($block->save()) {
                return $newvalue;
            }
       }
        return "error";
    }

    public function editBlockText()
    {
       $origValue = Input::get('origValue');
       $newvalue = Input::get('newvalue');
       $blockId = Input::get('blockId');

       $block = $this->post->where('id', '=', $blockId)->first();

       if ($block) {
            $block->content = $newvalue;

            if($block->save()) {
                return $newvalue;
            }
       }
        return "error";
    }

    public function editBlockPhoto()
    {
       $blockTitle = Input::get('block_title');
       $blockId = Input::get('block_id');
       $block = $this->post->where('title', '=', $blockTitle)->first();

       $extension = Input::file('block_photo')->getClientOriginalExtension();
       $input = Input::file('block_photo');
       $directory = public_path().'/uploads/'.(Auth::user()->username).'/blocks/';
       $filename = Input::file('block_photo')->getClientOriginalName();
       Input::file('block_photo')->move($directory, $filename);
       $upload_success = Input::file('block_photo', $directory, $filename);

       if (Input::hasFile('block_photo')) {
            if( $upload_success ) {

                $block->photos  = 1;
                $block->file_name = $filename;
                $block->photo_location = 'http://daylight.cc/uploads/'.(Auth::user()->username).'/blocks/'.$filename;
            }
        }
            
       if($block->save())
            {
                return Redirect::to('/block/'.$blockId);
            }
        return Redirect::to('/block/'.$blockId)->with('error', Lang::get('Could not change block photo'));
    }

    public function addComment() 
    {
        $commentContent = Input::get('comment');
        $blockId = Input::get('block_id');
        $user = Auth::user();

        

        $rules = array(
            'comment' => 'required|min:3'
        );

        $validator = Validator::make(Input::all(), $rules);

        // Check if the form validates with success
        if ($validator->passes())
        {

            $comment = new Comment;
            $comment->content = $commentContent;
            $comment->post_id = $blockId;
            $comment->user_id = $user->id;
            if (Input::hasFile('comment_photo')) {
           
               $extension = Input::file('comment_photo')->getClientOriginalExtension();
               $input = Input::file('comment_photo');
               $directory = public_path().'/uploads/'.(Auth::user()->username).'/comments/';
               $filename = Input::file('comment_photo')->getClientOriginalName();
               Input::file('comment_photo')->move($directory, $filename);
               $upload_success = Input::file('comment_photo', $directory, $filename);

                if( $upload_success ) {

                    $comment->photos  = 1;
                    $comment->file_name = $filename;
                    $comment->photo_location = 'http://daylight.cc/uploads/'.(Auth::user()->username).'/comments/'.$filename;
                }
            }

        }

        if($comment->save()) {
            return Redirect::to('/block/'.$blockId);
        }
        return Redirect::to('/block/'.$blockId)->with('error', Lang::get('Could not add Comment'));

    }



}