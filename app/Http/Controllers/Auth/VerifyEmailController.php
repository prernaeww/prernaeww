<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function __invoke(EmailVerificationRequest $request)
    // {
    //     print_r($request->user()->hasVerifiedEmail());exit;
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    //     }

    //     if ($request->user()->markEmailAsVerified()) {
    //         event(new Verified($request->user()));
    //     }

    //     return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    // }

    public function verify($id,Request $request)
    {
        $user = User::find($id);
        if(isset($user)){
            User::whereId($id)->update(['email_verified_at'=>date('Y-m-d H:i:s') , 'status' => 1]);
            if($user->group == 2){
                return view('auth.set-password', ['user' => $user]);
            }else{
                return view('auth.set-success');
            }
        }
    }
    public function set($id,Request $request)
    {
        $user = User::find($id);
        if(isset($user)){
            $password = Hash::make($request->password);
            User::whereId($user->id)->update(['password'=> $password]);
            return redirect()->route('canteen.login')
                        ->with('success','Password set successfully');
        }
    }
}
