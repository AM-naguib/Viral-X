<?php

namespace App\Http\Controllers\Back;

use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::select("id", "name", "email", "role_id", "plan_id")->get();
        return view("back.dashboard.users.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::get();
        $plans = Plan::get();
        return view("back.dashboard.users.create", compact("roles", "plans"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "role_id" => "required|exists:roles,id",
            "plan_id" => "required|exists:plans,id",
        ]);
        $data["password"] = bcrypt($data["password"]);
        User::create($data);
        return redirect()->route("admin.users.index")->with("success", "User created successfully");
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::get();
        $plans = Plan::get();

        return view("back.dashboard.users.edit", compact("user", "roles", "plans"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email,{$user->id}",
            "password" => "nullable",
            "role_id" => "required|exists:roles,id",
            "plan_id" => "required|exists:plans,id",
        ]);
        if (isset($data["password"])) {
            $data["password"] = bcrypt($data["password"]);
        }else{
            unset($data["password"]);
        }
        $user->update($data);
        return redirect()->route("admin.users.index")->with("success", "User updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with("success", "User deleted successfully");
    }
}
