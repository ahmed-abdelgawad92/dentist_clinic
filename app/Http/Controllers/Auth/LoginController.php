<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    /*
      Change authenticating to be with username
    */
    public function username()
    {
      return 'uname';
    }
    /*
    * show login form
    */
    public function getLogin()
    {
      // code...#
      if (Auth::check()) {
        // code...
        return redirect()->route("home");
      }else {
        // code...
        return view("login");
      }
    }
    public function authenticate(Request $request)
    {
      $credentials = $request->only('uname', 'password');
      if (Auth::attempt($credentials,false)) {
         // Authentication passed...
         if(Auth::user()->deleted==1){
           Auth::logout();
           return redirect()->back()->withInput()->with("invalid","Username or password is incorrect!");
         }
         return redirect()->intended('home');
      }else{
        return redirect()->back()->withInput()->with("invalid","Username or password is incorrect!");
      }
    }
    public function logout()
    {
      // code...
      Auth::logout();
      return redirect()->route('login');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
