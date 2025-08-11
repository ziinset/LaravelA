<?php

namespace App\Http\Controllers;

use App\Models\admin;
use Hash;
use Illuminate\Http\Request;

class adminController extends Controller
{
    public function landing(){
        return view('landing');
    }
    public function formLogin(){
        return view('login');
    }
    public function prosesLogin(Request $request){
        $admin = admin::where('username', $request->username)->first();
        if($admin && Hash::check($request->password, $admin->password)){
            //simpan ke session
            session(['admin_id' => $admin->id, 'admin_username' => $admin->username]);
            return redirect() -> route('home');
    }
    }
    public function logout(){
        //hapus session
        session() -> forget(['admin_id', 'admin_username']);
        return redirect() -> route('landing');
    }
}