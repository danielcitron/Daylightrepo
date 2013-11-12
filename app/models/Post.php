<?php

use Illuminate\Support\Facades\URL; 
use Robbo\Presenter\PresentableInterface;

class Post extends Eloquent implements PresentableInterface {

	public function boards()
	{
		return $this->belongsTo('Boards');
	}


	public function delete()
	{
		$this->comments()->delete();

		return parent::delete();
	}

	public function content()
	{
		return nl2br($this->content);
	}

	public function author()
	{
		return $this->belongsTo('User', 'user_id');
	}

	
	public function comments()
	{
		return $this->hasMany('Comment');
	}

    public function date($date=null)
    {
        if(is_null($date)) {
            $date = $this->created_at;
        }

        return String::date($date);
    }

	public function url()
	{
		return Url::to($this->slug);
	}

	public function created_at()
	{
		return $this->date($this->created_at);
	}

	public function updated_at()
	{
        return $this->date($this->updated_at);
	}

    public function getPresenter()
    {
        return new PostPresenter($this);
    }

}
