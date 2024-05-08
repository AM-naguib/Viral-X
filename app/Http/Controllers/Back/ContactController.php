<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
        $contacts = Contact::get();
        return view("back.dashboard.contacts.index", compact("contacts"));
    }

    public function store(Request $request){
        $data = $request->validate([
            "first_name" => "required|string|max:50",
            "last_name" => "required|string|max:50",
            "email" => "required|email",
            "phone_number" => "required|",
            "message" => "required|string|max:500",
        ]);
        Contact::create($data);
        return redirect()->route("front.contact")->with("success", "Message Sent Successfully");

    }

    public function show(Contact $contact){
        $contact->status = "read";
        $contact->save();
        return view("back.dashboard.contacts.show", compact("contact"));
    }
}
