<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        
        if(Auth::user()->hasRole()==1)
        {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME)->with('success','You have been successfully logged in.');
        }
        return redirect('/admin/login')->with('error','Your email or password is incorrect.');
        
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function board_create()
    {
        return view('auth.boardlogin');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function board_store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        if(Auth::user()->hasRole()==2)
        {
            if(Auth::user()->isActive()){
                return redirect()->intended(RouteServiceProvider::BOARD)->with('success','You have been successfully logged in.');
            }else{
                return redirect('/board/login')->with('error','Sorry! Your account has been inactive by the system');
            }
        }

        return redirect('/board/login')->with('error','Your email or password is incorrect.');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function board_destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/board/login');
    }

    public function store_create()
    {
        return view('auth.storelogin');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        if(Auth::user()->hasRole()==3)
        {
            if(Auth::user()->isActive()){
                return redirect()->intended(RouteServiceProvider::STORE)->with('success','You have been successfully logged in.');
            }else{
                return redirect('/store/login')->with('error','Sorry! Your account has been inactive by the system');
            }
        }

        return redirect('/store/login')->with('error','Your email or password is incorrect.');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/store/login');
    }

    public function customer_create()
    {
        $auth = Auth::user();
        if(isset($auth))
        {
            return redirect()->back();
        }
        return view('auth.customerlogin');
    }

    public function customer_store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        //dd(Auth::user()->hasRole());
        if(Auth::user()->hasRole()==4)
        {
            if(Auth::user()->isActive()){
                $accessToken = Auth::user()->createToken('authToken')->accessToken;
                Session::put('token', $accessToken);
                return redirect()->intended(RouteServiceProvider::CUSTOMER)->with('success','You have been successfully logged in.');
            }else{
                Auth::guard('web')->logout();
                return redirect('/login')->with('error','Sorry! Your account has been inactive by the system');
            }
        }else{
            Auth::guard('web')->logout();
            return redirect('/login')->with('error','Incorrect email or password!');
        }
        
        
    }


    public function customer_destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/home');
    }
}
