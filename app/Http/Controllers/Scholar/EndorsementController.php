<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EndorsementController extends Controller
{
    public function index(){
        return inertia('Modules/Scholars/Endorsements/Index');
    }
}
