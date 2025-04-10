<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }
    public function loginSubmit(Request $request){

        //form validation
        $request->validate(
            [
                'text_username' => 'required|email',
                'text_password' => 'required|min:6|max:16',
            ],
            [
                'text_username.required' => 'O username é Obrigatório',
                'text_username.email' => 'O username deve ser um email Válido',
                'text_password.required' => 'O campo password é Obrigatório',
                'text_password.min' => 'O campo password tem que ter no minimo min: caracteres',
                'text_password.max' => 'O campo password não pode ter mais de max: caracteres',
                
            ]
        );

        //get user input
        $username = $request->input('text_username');
        $password = $request->input('text_password');
        
        // check if user exists
        $user = User::where('username',$username)
                    ->where('deleted_at',NULL)
                    ->first();
        
        if(!$user){
            return redirect()->back()->withInput()->with('loginError','Username ou password incorretos.');
        }

        // check if password is correct
        if(!password_verify($password,$user->password)){
            return redirect()->back()->withInput()->with('loginError','Username ou password incorretos.');
        }
        //Update last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        //login user
        session([
            'user' =>[
                'id' => $user->id,
                'username' => $user->username
            ]
        ]);

        // redirect to rome

        return redirect()->to('/');
    }
    public function logout(){
        // logout from the application
        session()->forget('user');
        return redirect()->to('/login');
    }
}
