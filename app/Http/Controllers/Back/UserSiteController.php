<?php

namespace App\Http\Controllers\Back;

use App\Models\FbPage;
use App\Models\SiteData;
use App\Models\UserSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sites = UserSite::all();
        return view("back.dashboard.sites.index", compact("sites"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = FbPage::where("user_id", auth()->user()->id)->get();

        return view("back.dashboard.sites.create" ,compact("pages"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'site_name' => "required",
            "site_link" => "required|url|unique:user_sites,site_link",
            "post_link_selector" => "required",
            "post_title_selector" => "required",
            "pages" => "required",
        ]);

            foreach ($request->pages as $page) {
                $userPage = FbPage::find($page);
                if($userPage->user_id != auth()->user()->id){
                    abort(403);
                }
            }
        $data["user_id"] = auth()->user()->id;
        $userSite=UserSite::create($data);
        $userSite->fbPages()->sync($request->pages);
        return back()->with("success", "Site created successfully");

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserSite $site)
    {
        if($site->user_id != auth()->user()->id){
            abort(403);
        }
        $pages = FbPage::where("user_id", auth()->user()->id)->get();
        return view("back.dashboard.sites.edit", compact("site","pages"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserSite $site)
    {
        if($site->user_id != auth()->user()->id){
            abort(403);
        }
        $data = $request->validate([
            'site_name' => "required",
            "site_link" => "required|url|unique:user_sites,site_link,{$site->id}",
            "post_link_selector" => "required",
            "post_title_selector" => "required",
            "pages" => "required",
        ]);
        $site->update($data);
        $site->fbPages()->detach();
        $site->fbPages()->sync($request->pages);
        return redirect()->route("admin.sites.index")->with("success", "Site updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSite $site)
    {
        if($site->user_id != auth()->user()->id){
            abort(403);
        }
        $site->delete();
        return back()->with("success", "Site deleted successfully");

    }
}
