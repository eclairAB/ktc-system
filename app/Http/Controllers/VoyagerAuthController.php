<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Client;
use App\Models\Checker;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;

class VoyagerAuthController extends Controller
{
    use AuthenticatesUsers;

    public function login()
    {
        if ($this->guard()->user()) {
            return redirect()->route('voyager.dashboard');
        }

        return Voyager::view('voyager::login');
    }

    public function postLogin(Request $request)
    {
        $user_credential = $request;
        $user_credential['email'] = $this->findUserCredential($request);
        $this->validateLogin($user_credential);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($user_credential)) {
            $this->fireLockoutEvent($user_credential);

            return $this->sendLockoutResponse($user_credential);
        }

        $credentials = $this->credentials($user_credential);

        if ($this->guard()->attempt($credentials, $user_credential->has('remember'))) {
            return $this->sendLoginResponse($user_credential);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($user_credential);

        return $this->sendFailedLoginResponse($user_credential);
    }

    /*
     * Preempts $redirectTo member variable (from RedirectsUsers trait)
     */
    public function redirectTo()
    {
        return config('voyager.user.redirect', route('voyager.dashboard'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard(app('VoyagerGuard'));
    }

    protected function findUserCredential($request)
    {
        $payload = $request;
        if(filter_var($payload->account_identity, FILTER_VALIDATE_EMAIL))
        {
            return $payload->account_identity;
        }
        else
        {
            // ----------- inner join on Staff -----------
            $staff = User::join('staff', 'users.id', 'staff.account_id')
                ->select('users.email')
                ->where('staff.user_id', $payload->account_identity)
                ->first();
            if($staff) return $staff->email;
            // ----------- inner join on Checker -----------
            $checker = User::join('checkers', 'users.id', 'checkers.account_id')
                ->select('users.email')
                ->where('checkers.user_id', $payload->account_identity)
                ->first();
            if($checker) return $checker->email;
            // ----------- inner join on Client -----------
            $client = User::join('clients', 'users.id', 'clients.account_id')
                ->select('users.email')
                ->where('clients.user_id', $payload->account_identity)
                ->first();
            if($client) return $client->email;
        }
    }
}
