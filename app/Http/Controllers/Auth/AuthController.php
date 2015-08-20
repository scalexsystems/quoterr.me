<?php

namespace Quoterr\Http\Controllers\Auth;

use Auth;
use Quoterr\Http\Controllers\Controller;
use Quoterr\User;
use Socialize;

class AuthController extends Controller
{
    protected $providers = [
        'twitter',
        'facebook',
    ];

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function redirectToProvider($provider)
    {
        if (!in_array($provider, $this->providers, true)) {
            abort(404);
        }

        return Socialize::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        if (!in_array($provider, $this->providers, true)) {
            abort(404);
        }

        $try = Socialize::driver($provider)->user();

        if ($try->getEmail()) {
            $user = User::whereEmail($try->getEmail())->first();
        } else {
            $user = User::whereIdentifier($try->getId())->first();
        }

        if (!$user) {
            $user = User::create(
                [
                    'name'       => $try->getName(),
                    'email'      => $try->getEmail(),
                    'identifier' => $try->getId(),
                ]
            );
        }

        Auth::login($user);

        return response()->redirectToIntended();
    }

    public function login()
    {
        return view('login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->home();
    }
}
