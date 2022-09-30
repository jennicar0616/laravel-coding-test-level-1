<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function index(){
     return view('login');
    }

    function checklogin(Request $request){
        $this->validate($request, [
        'email'   => 'required|email',
        'password'  => 'required'
        ]);

        $user_data = array(
        'email'  => $request->get('email'),
        'password' => $request->get('password')
        );

        if(Auth::attempt($user_data)) {
            return redirect('login/successlogin');
        } else {
            return back()->with('error', 'Wrong Login Details');
        }
    }

    function successlogin(){
        return redirect('events');
    }

    function logout(){
        Auth::logout();
        return redirect('login');
    }
}
