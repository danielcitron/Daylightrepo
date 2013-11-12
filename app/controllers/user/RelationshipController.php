<?php

class RelationshipController extends AdminController {

	protected $relationship;

	public function __construct(Relationships $relationship)
    {
        parent::__construct();
        $this->relationships = $relationship;
    }

    public function subscribe() 
    {
    	$title = Input::get('board_title');
    	$user = Auth::user();
    	$board_id = Input::get('board_id');
    	//$board = $boards->where('title', '=', $title)->first();
    	$relationships = DB::table('relationships')->get();

    	$relationship = new Relationships;
    	$relationship->user_id = $user->id;
    	$relationship->boards_id = $board_id;
    	$relationship->save();

    	return Redirect::to('/boards/'.$board_id);
    }

    public function unsubscribe() 
    {
        $title = Input::get('board_title');
        $user = Auth::user();
        $board_id = Input::get('board_id');
        $subscription = $user->relationships()->where('boards_id', '=', $board_id);

        if($subscription) {
            $subscription->delete();

            $checker = Relationships::find($board_id);
            if(empty($checker))
            {
                // Redirect to  boards
                return Redirect::to('/boards/'.$board_id);
            }
        }

        return Redirect::to('/boards/'.$board_id);
    }
}