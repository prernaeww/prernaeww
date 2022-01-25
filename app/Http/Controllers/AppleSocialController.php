<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\AppleToken;
use App\Traits\ApiWebsite;
use App\Providers\RouteServiceProvider;
use Session;

class AppleSocialController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    use ApiWebsite;

    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function handleCallback(AppleToken $appleToken)
    {
      // config()->set('services.apple.client_secret', $appleToken->generate());
      // $socialUser = Socialite::driver('apple')->stateless()->user();

      $user = Socialite::driver("sign-in-with-apple")->user();

      $api = 'social-login';
      $method = 2;
      $variables = array(
          'email' => $user->email,
          'device_type' => 'web',
          'device_token' => 'web',
          'social_id' => $user->id,
          'social_type' => 'apple',
          'first_name' => 'Apple',
          'last_name' => 'user'
      );
      $response = $this->api_call($api, $method, $variables);

      if ($response['status'] == TRUE) {
          $credentials['email'] = $response['data']['email'];
          $credentials['password'] = $response['data']['email'];
          if (Auth::attempt($credentials)) {
              Session::put('user', $response['data']);
              $accessToken = Auth::user()->createToken('authToken')->accessToken;
              Session::put('token', $accessToken);
              //return redirect()->intended(RouteServiceProvider::CUSTOMER)->with('success', 'You have been successfully logged in.');
              return redirect('account?verify=mobile')->with('success', 'You have been successfully logged in.');
          } else {
              return redirect('/login')->with('error', 'Incorrect email or password!');
          }
      } else {
          return redirect()->route('login')->with('error', $response['message']);
      }
 
    // Further actions you want to take in your app...
	}
}
