<?php
use App\Http\Middleware\PremiumPlan;
use App\Http\Middleware\StandardPlan;
use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use App\Jobs\postScrapingData;
use App\Http\Middleware\OnlyAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Back\PostController;
use App\Http\Controllers\Back\UserController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Back\FbPageController;
use App\Http\Controllers\Back\ContactController;
use App\Http\Controllers\Back\FbGroupController;
use App\Http\Controllers\Back\TwitterController;
use App\Http\Controllers\Back\SiteDataController;
use App\Http\Controllers\Back\UserSiteController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\ScheduledPostController;
use App\Http\Controllers\Back\SocialAccountController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('hi', [HomeController::class, 'hi']);

Route::get("test", function () {
    $post = ScheduledPost::findOrFail(1);
    if($post->image_url == null){
        dd("null");
    }else{
        dd("not null");
    }
});

// back routes
Route::middleware('auth')->prefix('dashboard')->name('admin.')->group(function () {
    Route::get("/", [DashboardController::class, 'index'])->name('dashboard');
    Route::get("pricing", [DashboardController::class, 'pricing'])->name('pricing');

    Route::middleware(StandardPlan::class)->group(function () {
        Route::get("social-accounts", [SocialAccountController::class, 'index'])->name('social-accounts');
        Route::delete("social-accounts/{id}", [SocialAccountController::class, 'destroy'])->name('social-accounts.destroy');
        // Facebook Group Routes
        // NOW API FOR GROUPS IS NOT WORKING :(
        // Route::get("social-accounts/get-groups", [FbGroupController::class, 'getGroups'])->name('groups.get');
        // Route::get("social-accounts/show-groups", [FbGroupController::class, 'index'])->name('fbgroups.show');
        // Route::post("posts/groups-send-post", [FbGroupController::class, "groupsSendPost"])->name("posts.groups-send-post");
        // Facebook Page Routes
        Route::get("social-accounts/get-pages", [FbPageController::class, 'getPages'])->name('fbpages.get');
        Route::get("social-accounts/show-pages", [FbPageController::class, 'index'])->name('fbpages.index');
        Route::post("posts/pages-send-post", [FbPageController::class, "pagesSendPost"])->name("posts.pages-send-post");
        // Posts Routes
        Route::get("posts/pages-add-post", [PostController::class, "pagesAddPost"])->name("posts.pages-add-post");
        // Route::get("posts/groups-add-post", [PostController::class, "groupsAddPost"])->name("posts.groups-add-post");
        Route::get("posts/twitter-add-post", [PostController::class, "twitterAddPost"])->name("posts.twitter-add-post");
        // Twitter Routes
        Route::post("posts/twitter-send-post", [TwitterController::class, "twitterSendPost"])->name("posts.twitter-send-post");
        // History Routes
        Route::get("history", [DashboardController::class, 'history'])->name('history');
    });


    Route::middleware(PremiumPlan::class)->group(function () {
        // ScheduledPosts Routes
        Route::resource("posts/scheduled-posts", ScheduledPostController::class);
        // Sites Routes
        Route::resource("sites", UserSiteController::class);
    });

    Route::middleware(OnlyAdmin::class)->group(function () {
        // Plans Routes
        Route::resource("plans", PlanController::class);
        // Users Routes
        Route::resource("users", UserController::class);
        // Contact Routes
        Route::get("contact", [ContactController::class, "index"])->name('contact.index');
        Route::get("contact/{contact}", [ContactController::class, "show"])->name('contact.show');

    });

    // Scraping Routes
    Route::get("scraping", [SiteDataController::class, 'getPosts'])->name('scraping');
    // Post Sites Data
    // Route::get("postSitesData", [PostScrapingData::class, "index"])->name('postSitesData');
});
Route::post("contact/store", [ContactController::class, "store"])->name('contact.store');

// front routes
Route::name("front.")->group(function () {
    Route::get("/", [HomeController::class, 'index'])->name("index");
    Route::get("contact", [HomeController::class, 'contact'])->name("contact");
    Route::get("pricing", [HomeController::class, 'pricing'])->name("pricing");
    Route::get("refund-policy", [HomeController::class, 'refundPolicy'])->name("refund-policy");
});

Route::get("get", function (Request $request) {
    $data = $request->all();
    if (isset ($data['post_title']) && isset ($data['post_url']) && isset ($data['site_url'])) {

        $post_title_selector = $data['post_title'];
        $post_url_selector = $data['post_url'];
        $site_url = $data['site_url'];

        $client = new Goutte\Client();
        $res = $client->request("GET", $site_url);

        $result = [];

        $res->filter($post_title_selector)->each(function ($titleNode, $i) use ($res, $post_url_selector, &$result) {
            $urlNode = $res->filter($post_url_selector)->eq($i);
            $title = $titleNode->text();
            $url = $urlNode->attr("href");
            $result[] = ['title' => $title, 'url' => $url];
        });

        // Return the result as JSON
        return response()->json($result);
    }

    // Return an error response if required parameters are missing
    return response()->json(['error' => 'Missing parameters'], 400);
});





// socialise routes
Route::get("auth/{provider}", [SocialAccountController::class, 'provider']);
Route::get("auth/{provider}/callback", [SocialAccountController::class, 'callback']);


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
