<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StaffService;

class StaffController extends Controller
{
    public $staff;

    public function __construct(StaffService $staff)
    {
        $this->staff = $staff;
    }

    public function update(Request $request){
        switch($request->type){
            case 'verification':
                return $this->verification($request);
            break;
            default: 
                return $this->staff->editStaff($request);
        }
    }
}
