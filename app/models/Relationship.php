<?php

class Relationships extends Eloquent
{

        public function user()
        {
                return $this->belongsTo('User');
        }

        public function posts()
        {
                return $this->hasMany('Post');
        }

        public function boards()
        {
            return $this->belongsTo('Boards');
        }
}