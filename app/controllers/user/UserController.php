<?php

class UserController extends BaseController {


    protected $user;
    protected $boards;
    protected $post;
    protected $comment;


    public function __construct(User $user, Boards $board, Post $post, Comment $comment, Relationships $relationship)
    {
        parent::__construct();
        $this->user = $user;
        $this->boards = $board;
        $this->post = $post;
        $this->comment = $comment;
        $this->relationship = $relationship;
    }


    public function getIndex()
    {
        list($user,$redirect) = $this->user->checkAuthAndRedirect('user');
        if($redirect){return $redirect;}

        // Show the page
        return View::make('site/user/index', compact('user'));
    }



    public function getUserProfile($username)
    {
         if(Auth::user()){

        $currentUser = Auth::user();
        $userProfile = $this->user->where('username', '=', $username)->first();
        $userBoards = $this->boards->where('user_id', '=', $userProfile->id)->orderBy('created_at', 'DESC')->get();
        $userBlocks = $this->post->where('user_id', '=', $userProfile->id)->orderBy('created_at', 'DESC')->get();
        $userSubscriptions = $this->relationship->where('user_id', '=', $userProfile->id)->orderBy('created_at', 'DESC')->get();

        // Show the page
        return View::make('user/profiles/profile', compact('currentUser', 'userProfile', 'userBoards', 'userBlocks', 'userSubscriptions', 'randomBoards'));
        }

        return View::make('site/user/login');
    }


    public function getUserHome()
    {
        if(Auth::user()){

            $currentUser = Auth::user();
            $userProfile = Auth::user();
            $userBoards = $this->boards->where('user_id', '=', $userProfile->id)->orderBy('created_at', 'DESC')->get();
            $userBlocks = $this->post->where('user_id', '=', $userProfile->id)->orderBy('created_at', 'DESC')->get();
            $userSubscriptions = $this->relationship->where('user_id', '=', $userProfile->id)->orderBy('created_at', 'DESC')->get();

            return View::make('user/profiles/profile', compact('currentUser', 'userProfile', 'userBoards', 'userBlocks', 'userSubscriptions', 'randomBoards'));
        }

        else {

            return View::make('site/user/login', compact('posts'));
        }
    }

    public function postIndex()
    {
        $this->user->username = Input::get( 'username' );
        $this->user->email = Input::get( 'email' );

        $password = Input::get( 'password' );
        $passwordConfirmation = Input::get( 'password_confirmation' );

        if(!empty($password)) {
            if($password === $passwordConfirmation) {
                $this->user->password = $password;
                $this->user->password_confirmation = $passwordConfirmation;
            } else {
                return Redirect::to('/')
                    ->withInput(Input::except('password','password_confirmation'))
                    ->with('error', Lang::get('Passwords do not match'));
            }
        } else {
            unset($this->user->password);
            unset($this->user->password_confirmation);
        }


        $this->user->save();

        if ( $this->user->id )
        {
            return Redirect::to('user/login');
        }
        else
        {
            $error = $this->user->errors()->all();

            return Redirect::to('/')
                ->withInput(Input::except('password'))
                ->with( 'error', $error );
        }
    }

    public function postEdit($user)
    {
        // Validate the inputs
        $validator = Validator::make(Input::all(), $user->getUpdateRules());


        if ($validator->passes())
        {
            $oldUser = clone $user;
            $user->username = Input::get( 'username' );
            $user->email = Input::get( 'email' );

            $password = Input::get( 'password' );
            $passwordConfirmation = Input::get( 'password_confirmation' );

            if(!empty($password)) {
                if($password === $passwordConfirmation) {
                    $user->password = $password;
                    $user->password_confirmation = $passwordConfirmation;
                } else {
                    return Redirect::to('users')->with('error', Lang::get('admin/users/messages.password_does_not_match'));
                }
            } else {
                unset($user->password);
                unset($user->password_confirmation);
            }

            $user->prepareRules($oldUser, $user);

            $user->amend();
        }

        $error = $user->errors()->all();

        if(empty($error)) {
            return Redirect::to('user')
                ->with( 'success', Lang::get('user/user.user_account_updated') );
        } else {
            return Redirect::to('user')
                ->withInput(Input::except('password','password_confirmation'))
                ->with( 'error', $error );
        }
    }

    public function getCreate()
    {
        return View::make('site/user/create');
    }



    public function getLogin()
    {
        $user = Auth::user();
        if(!empty($user->id)){
            return Redirect::to('/');
        }

        return View::make('site/user/login');
    }


    public function postLogin()
    {
        $input = array(
            'email'    => Input::get( 'email' ), // May be the username too
            'username' => Input::get( 'email' ), // May be the username too
            'password' => Input::get( 'password' ),
            'remember' => Input::get( 'remember' ),
        );

        if ( Confide::logAttempt( $input, true ) )
        {
            $r = Session::get('loginRedirect');
            if (!empty($r))
            {
                Session::forget('loginRedirect');
                return Redirect::to($r);
            }
            return Redirect::to('/');
        }
        else
        {
            // Check if there was too many login attempts
            if ( Confide::isThrottled( $input ) ) {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            } elseif ( $this->user->checkUserExists( $input ) && ! $this->user->isConfirmed( $input ) ) {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            } else {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

            return Redirect::to('user/login')
                ->withInput(Input::except('password'))
                ->with( 'error', $err_msg );
        }
    }


    public function getConfirm( $code )
    {
        if ( Confide::confirm( $code ) )
        {
            return Redirect::to('user/login')
                ->with( 'notice', Lang::get('confide::confide.alerts.confirmation') );
        }
        else
        {
            return Redirect::to('user/login')
                ->with( 'error', Lang::get('confide::confide.alerts.wrong_confirmation') );
        }
    }


    public function getForgot()
    {
        return View::make('site/user/forgot');
    }

    public function postForgot()
    {
        if( Confide::forgotPassword( Input::get( 'email' ) ) )
        {
            return Redirect::to('user/login')
                ->with( 'notice', Lang::get('confide::confide.alerts.password_forgot') );
        }
        else
        {
            return Redirect::to('user/forgot')
                ->withInput()
                ->with( 'error', Lang::get('confide::confide.alerts.wrong_password_forgot') );
        }
    }

    public function getReset( $token )
    {

        return View::make('site/user/reset')
            ->with('token',$token);
    }


    public function postReset()
    {
        $input = array(
            'token'=>Input::get( 'token' ),
            'password'=>Input::get( 'password' ),
            'password_confirmation'=>Input::get( 'password_confirmation' ),
        );

        if( Confide::resetPassword( $input ) )
        {
            return Redirect::to('user/login')
            ->with( 'notice', Lang::get('confide::confide.alerts.password_reset') );
        }
        else
        {
            return Redirect::to('user/reset/'.$input['token'])
                ->withInput()
                ->with( 'error', Lang::get('confide::confide.alerts.wrong_password_reset') );
        }
    }


    public function getLogout()
    {
        Confide::logout();

        return Redirect::to('/');
    }


    public function getProfile($username)
    {
        $userModel = new User;
        $user = $userModel->getUserByUsername($username);

        // Check if the user exists
        if (is_null($user))
        {
            return App::abort(404);
        }

        return View::make('site/user/profile', compact('user'));
    }

    public function getSettings()
    {
        list($user,$redirect) = User::checkAuthAndRedirect('user/settings');
        if($redirect){return $redirect;}

        return View::make('site/user/profile', compact('user'));
    }


    public function processRedirect($url1,$url2,$url3)
    {
        $redirect = '';
        if( ! empty( $url1 ) )
        {
            $redirect = $url1;
            $redirect .= (empty($url2)? '' : '/' . $url2);
            $redirect .= (empty($url3)? '' : '/' . $url3);
        }
        return $redirect;
    }
}
