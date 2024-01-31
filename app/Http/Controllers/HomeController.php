<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class HomeController extends Controller
{
    public function index(){
        return inertia('Modules/Dashboard/Index');
    }

    public function installation(){
        if(\Auth::user()->is_active){
            return redirect()->intended(RouteServiceProvider::HOME);
        }else{
            if(\Auth::user()->role == 'Administrator'){
                return inertia('Installation');
            }
        }
    }

    public function test(){
        $data = '10 http://dost-stsims-main.final 2|Rd119006K2FJ8srPLThx5SmdCrdZwk0NHUFyTAJv7a588e72';
        return encrypt($data);
    }
}
