<?php

class AuthorizedController extends BaseController
{
	protected $whitelist = array();

	public function __construct()
	{
        parent::__construct();
		$this->beforeFilter('auth', array('except' => $this->whitelist));
	}
}
