<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Insight\CountService;

class InsightController extends Controller
{
    public function __construct(CountService $count)
    {
        $this->count = $count;
    }

    public function index(Request $request){
        return inertia('Modules/Insights/Index',['statistics' => $this->count->statistics()]);
    }
}
