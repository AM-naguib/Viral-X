<?php

namespace App\Http\Controllers\Back;

use DateTime;
use Carbon\Carbon;
use App\Models\FbPage;
use App\Models\UserSite;
use Illuminate\Http\Request;
use App\Models\ScheduledPost;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ScheduledPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = ScheduledPost::where("user_id", auth()->user()->id)->get();
        return view("back.dashboard.scheduler-posts.index", compact("posts"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $pages = FbPage::where("user_id", auth()->user()->id)->select("id", "name", "page_id")->get();

        return view("back.dashboard.scheduler-posts.create", compact("pages"));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "content" => ["required", "string", "max:1000"],
            "image" => ["nullable", "image", "mimes:png,jpg,jpeg,webp"],
            "postTime" => ["required", "date", "after:now"],
            "pages" => ["required", "array"],
        ]);
        $imagePath = "";
        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("public");
        }

        $scheduledPost = new ScheduledPost();
        $scheduledPost->user_id = auth()->user()->id;
        $scheduledPost->content = $request->content;
        $scheduledPost->image_url = $imagePath;
        $scheduledPost->scheduled_at = $request->postTime;
        $scheduledPost->save();
        $scheduledPost->fbPages()->sync($request->pages);
        return back()->with("success", "Scheduled Post Created Successfully");



    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduledPost $scheduledPost)
    {
        if($scheduledPost->user_id != auth()->user()->id){
            abort(403);
        }
        $pages = FbPage::where("user_id", auth()->user()->id)->select("id", "name", "page_id")->get();

        return view("back.dashboard.scheduler-posts.edit",compact("scheduledPost","pages"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScheduledPost $scheduledPost)
    {
        if($scheduledPost->user_id != auth()->user()->id){
            abort(403);
        }
        $request->validate([
            "content" => ["required", "string", "max:1000"],
            "image" => ["nullable", "image", "mimes:png,jpg,jpeg,webp"],
            "postTime" => ["required", "date", "after:now"],
            "pages" => ["required", "array"],
        ]);
        $imagePath = $scheduledPost->image_url;
        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("public");
        }

        $scheduledPost->content = $request->content;
        $scheduledPost->image_url = $imagePath;
        $scheduledPost->scheduled_at = $request->postTime;
        $scheduledPost->save();
        $scheduledPost->fbPages()->detach();
        $scheduledPost->fbPages()->sync($request->pages);
        return redirect()->route("admin.scheduled-posts.index")->with("success", "Scheduled Post Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduledPost $scheduledPost)
    {

        if($scheduledPost->user_id != auth()->user()->id){
            abort(403);
        }
        $scheduledPost->delete();
        return back()->with("success", "Scheduled Post Deleted Successfully");
    }
}
