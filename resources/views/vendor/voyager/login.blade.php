@extends('voyager::auth.master')

@section('content')
    
    <style type="text/css">
        body.login .login-container {
            position: relative !important;
            z-index: 20 !important;
            width: 100% !important; 
            padding: unset !important;
            top: unset !important;
            margin-top: unset !important;
        }
        body.login .login-sidebar {
            display: flex !important;
            align-items: center !important;
            padding: 40px !important;
        }
        body.login .login-button {
            width: 100% !important;
            background: #6675df !important;
        }
        .login_credits {
            text-align: center;
            font-size: 12px;
            color: black;
        }
    </style>

    <div class="login-container">

        <div>
            <div style="color: black; font-weight: bold; text-align: center; font-size: 20px; margin-bottom: 30px ;">
                <img src = "{{ asset('/images/kudos.png') }}" width="200px" /><br>
            </div>
            <p>{{ __('voyager::login.signin_below') }}</p>

            <form action="{{ route('voyager.login') }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group form-group-default" id="emailGroup">
                    <label>Email / Username</label>
                    <div class="controls">
                        <input type="text" name="account_identity" id="email" value="{{ old('email') }}" placeholder="Email / Username" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group form-group-default" id="passwordGroup">
                    <label>{{ __('voyager::generic.password') }}</label>
                    <div class="controls">
                        <input type="password" name="password" placeholder="{{ __('voyager::generic.password') }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group" id="rememberMeGroup">
                    <div class="controls">
                        <input type="checkbox" name="remember" id="remember" value="1"><label for="remember" class="remember-me-text">{{ __('voyager::generic.remember_me') }}</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-block login-button" style="margin-bottom: 20px; background: #081e48 !important;">
                    <span class="signingin hidden"><span class="voyager-refresh"></span> {{ __('voyager::login.loggingin') }}...</span>
                    <span class="signin">{{ __('voyager::generic.login') }}</span>
                </button>

                <div class="login_credits">&#169; 2021</div>

            </form>

            <div style="clear:both"></div>

            @if(!$errors->isEmpty())
                <div class="alert alert-red">
                    <ul class="list-unstyled">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

    </div> <!-- .login-container -->
@endsection

@section('post_js')

    <script>
        var btn = document.querySelector('button[type="submit"]');
        var form = document.forms[0];
        var account_identity = document.querySelector('[name="account_identity"]');
        var password = document.querySelector('[name="password"]');
        btn.addEventListener('click', function(ev){
            if (form.checkValidity()) {
                btn.querySelector('.signingin').className = 'signingin';
                btn.querySelector('.signin').className = 'signin hidden';
            } else {
                ev.preventDefault();
            }
        });
        account_identity.focus();
        document.getElementById('emailGroup').classList.add("focused");

        // Focus events for email and password fields
        account_identity.addEventListener('focusin', function(e){
            document.getElementById('emailGroup').classList.add("focused");
        });
        account_identity.addEventListener('focusout', function(e){
            document.getElementById('emailGroup').classList.remove("focused");
        });

        password.addEventListener('focusin', function(e){
            document.getElementById('passwordGroup').classList.add("focused");
        });
        password.addEventListener('focusout', function(e){
            document.getElementById('passwordGroup').classList.remove("focused");
        });

    </script>
@endsection
