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

    public function hi()
    {
        $url = 'https://test-api.kashier.io/paymentRequest?currency=EGP';
        $headers = [
            'Authorization' => '8cf01d55a3f8068afffc5fba3f148b8c$3bbd013855a327c9996ea561d6955232e7f5f2700b7d3aceb638e00dc392dfd8ccce655aa5f06257e09c827e1d1a489b',
            'Content-Type' => 'application/json',
        ];
        $payload = [
            "paymentType" => "professional",
            "merchantId" => "MID-24407-76",
            "totalAmount" => 50,
            "customerName" => "test customer",
            "description" => "some description",
            "invoiceItems" => [
                [
                    "description" => "invoice item description",
                    "quantity" => 5,
                    "itemName" => "laptop",
                    "unitPrice" => 10,
                    "subTotal" => 50
                ]
            ],
            "state" => "submitted",
            "tax" => 0
        ];

        $response = Http::withHeaders($headers)->post($url, $payload);

        return $response->json();
    }

}
