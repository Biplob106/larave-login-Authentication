<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Hash;
use Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function Customlogin(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        $credentials=$request->only('email','password');
        if(Auth::attempt($credentials))
        {
            return redirect()->intended('auth.dashboard')->withSuccess('signed in');
        }
        return redirect("login")->withSuccess('Login details are not valid');
    }
    public function registration()
    {
        return view('auth.registation');
    }
    public function CustomRegistration(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);
        $data = $request->all();
        $check = $this->create($data);

        return redirect("auth.dashboard")->withSuccess('You have signed-in');
    }
    public function create(array $data)

    {
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password'])
        ]);
    }
    public function dashboard()
    {
        if(Auth::check()){
            return view('auth.dashboard');
        }
        return redirect("login")->withSuccess('you are not allow access');
    }
    public function signOut()
    {

        Auth::logout();
        return Redirect('login');
    }

}
