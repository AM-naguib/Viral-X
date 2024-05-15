<?php

namespace App\Http\Controllers\Front;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        return view('front.index');
    }
    public function contact()
    {
        return view("front.contact");
    }
    public function pricing()
    {

        $plans = Plan::where("status", "active")->get();
        return view("front.pricing", compact("plans"));
    }

    public function refundPolicy(){
        return view("front.refund-policy");
    }

}
