@extends('site.layouts.loggedout')


@section('title')
{{{ Lang::get('user/user.login') }}} ::
@parent
@stop


@section('content')
<div class="title-container">
    <div class="hero-title">daylight</div>
</div>
<div class="subheader">organize your inspiration</div>


    <div class="home-register-button">Register?</div>


<div class="input-container">
    <div class="input-subcontainer">
    <div class="login-pane">

        <form method="POST" action="{{ URL::to('user/login') }}" accept-charset="UTF-8">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <fieldset>
                <div class="username">
                        <input tabindex="1" placeholder="{{ Lang::get('confide::confide.username_e_mail') }}" type="text" name="email" id="email" value="{{ Input::old('email') }}">
                </div>
                <div class="password">
                        <input tabindex="2" placeholder="{{ Lang::get('confide::confide.password') }}" type="password" name="password" id="password">
                </div>
     


            <div class="form-group" style="display: none">
                <div class="col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label for="remember">{{ Lang::get('confide::confide.login.remember') }}
                            <input type="hidden" name="remember" value="0">
                            <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
                        </label>
                    </div>
                </div>
            </div>


                @if ( Session::get('error') )
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                @if ( Session::get('notice') )
                <div class="alert">{{ Session::get('notice') }}</div>
                @endif
                <div class="login-buttons">
                        <button tabindex="3" type="submit" class="btn btn-primary">{{ Lang::get('confide::confide.login.submit') }}</button>
                        <a style="display: none" class="btn btn-default" href="forgot">{{ Lang::get('confide::confide.login.forgot_password') }}</a>
                </div>

            </fieldset>
        </form>

    </div>

    <div class="login-pane register-pane">

           <form method="POST" action="{{{ (Confide::checkAction('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8">
            <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
            <fieldset>
                   
                    <input placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ Input::old('username') }}}">

                    <input placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">


                    <input placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">


                    <input placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation">


                @if ( Session::get('error') )
                    <div class="alert alert-error alert-danger">
                        @if ( is_array(Session::get('error')) )
                            {{ head(Session::get('error')) }}
                        @endif
                    </div>
                @endif

                @if ( Session::get('notice') )
                    <div class="alert">{{ Session::get('notice') }}</div>
                @endif

                <div class="form-actions form-group">
                  <button type="submit" class="btn btn-primary">{{{ Lang::get('confide::confide.signup.submit') }}}</button>
                </div>

            </fieldset>
        </form>


    </div>
</div>
</div>


@stop
