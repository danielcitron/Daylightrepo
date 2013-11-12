<?php

class Boards extends Eloquent
{
      //  public static $timestamps = true;

        public function user()
        {
                return $this->belongsTo('User');
        }

        public function posts()
        {
                return $this->hasMany('Post');
        }

        public function relationships()
        {
                return $this->hasMany('Relationships');
        }
	        
}