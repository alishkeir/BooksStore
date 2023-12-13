<?php
use Alomgyar\Customers\CustomerAuthController;
use Alomgyar\Customers\CustomerPasswordController;
use Alomgyar\Customers\CustomerPasswordResetLinkController;
use Alomgyar\Social\Controllers\Api\SocialLoginController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CartApiController;
use App\Http\Controllers\CheckoutApiController;
use App\Http\Controllers\CommentApiController;
use App\Http\Controllers\ContactApiController;
use App\Http\Controllers\CustomerApiController;
use App\Http\Controllers\HelpersApiController;
use App\Http\Controllers\MainPageApiController;
use App\Http\Controllers\NewsletterApiController;
use App\Http\Controllers\OrderApiController;
use App\Http\Controllers\PageContentApiController;
use App\Http\Controllers\PagesApiController;
use App\Http\Controllers\PostApiController;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\SearchApiController;
use App\Http\Controllers\SettingsApiController;
use App\Http\Controllers\StoreBasicGamingFormApiController;
use App\Http\Controllers\StoreListApiController;
use App\Http\Controllers\StorePrizeGamingFormApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('{store}/composite', ApiController::class);
    Route::get('{store}/product', ProductApiController::class);
    Route::get('{store}/pages/{slug}', PagesApiController::class);
    // LAST 4 BLOG ARTICLE
    Route::get('{store}/last-posts', [PostApiController::class, 'lastPosts']);
    // ---
    Route::post('{store}/newsletter', NewsletterApiController::class);
    Route::post('{store}/search', SearchApiController::class);
    Route::get('{store}/helpers', HelpersApiController::class);
    Route::post('{store}/carts', CartApiController::class);
    Route::post('{store}/order', CheckoutApiController::class);
    Route::get('{store}/comments', [CommentApiController::class, 'get']);
    Route::get('{store}/mainpage', MainPageApiController::class);
    Route::post('{store}/contact', ContactApiController::class);
    Route::get('{store}/order-status', OrderApiController::class);
    Route::get('{store}/page-content', PageContentApiController::class);

    Route::post('{store}/public/preorders', [CustomerApiController::class, 'storePublicPreOrder']);

    Route::post('{store}/order/{orderid}', [CheckoutApiController::class, 'getOrder']);

    // AUTH
    Route::post('{store}/register', [CustomerAuthController::class, 'store']);
    Route::post('{store}/login', [CustomerAuthController::class, 'login']);
    Route::post('{store}/forgot-password', [CustomerPasswordResetLinkController::class, 'store']);
    Route::put('{store}/update-password', [CustomerPasswordController::class, 'update']);
    Route::post('{store}/check-token', [CustomerAuthController::class, 'checkToken']);
    Route::put('{store}/update-current-password', [CustomerPasswordController::class, 'changePassword']);

    // SOCIAL AUTH
    Route::post('{store}/social/login', [SocialLoginController::class, '__invoke']);

    // Checkout
    Route::post('{store}/payment', [CheckoutApiController::class, 'create'])->name('checkout.create');
    Route::any('{store}/callback', [CheckoutApiController::class, 'callback'])->name('checkout.callback');
    Route::get('{store}/paymentcheck', [CheckoutApiController::class, 'barionCheck'])->name('checkout.check'); // ez csak dummy, a frontendre kell majd mutasson a redirect link

    Route::get('{store}/alomgyar/store-list', StoreListApiController::class)->name('prize-gaming-form.store');
    Route::post('{store}/prize-gaming-form/store', StorePrizeGamingFormApiController::class)->name('prize-gaming-form.store');
    Route::post('{store}/basic-gaming-form/store', StoreBasicGamingFormApiController::class)->name('basic-gaming-form.store');

    // Preferences
    Route::post('/{store}/metadata', [SettingsApiController::class, 'metadata'])->name('settings.metadata');

    Route::get('{store}/sitemap', [\App\Http\Controllers\SitemapController::class, 'index']);
    Route::get('{store}/sitemap/basic', [\App\Http\Controllers\SitemapController::class, 'basic']);
    Route::get('{store}/sitemap/product/{i}', [\App\Http\Controllers\SitemapController::class, 'products']);
    Route::get('{store}/sitemap/author/{i}', [\App\Http\Controllers\SitemapController::class, 'authors']);


    // PROTECTED ROUTES
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::post('{store}/logout', [CustomerAuthController::class, 'destroy']);

        // Profile
        Route::get('{store}/profile', [CustomerApiController::class, 'getPersonalDetails']);
        Route::post('{store}/profile/update', [CustomerApiController::class, 'update']);

        Route::get('{store}/profile/orders', [CustomerApiController::class, 'getCustomerOrders']);
        Route::get('{store}/profile/ebooks', [CustomerApiController::class, 'getCustomerEbooks']);

        Route::post('{store}/profile/download', [CustomerApiController::class, 'getDownloadEbook']);
        Route::post('{store}/profile/download-invoice', [CustomerApiController::class, 'getDownloadInvoice']);

        Route::get('{store}/profile/preorders', [CustomerApiController::class, 'getCustomerPreOrders']);
        Route::delete('{store}/profile/preorders', [CustomerApiController::class, 'destroyCustomerPreOrder']);
        Route::post('{store}/profile/preorders', [CustomerApiController::class, 'storeCustomerPreOrder']);

        Route::get('{store}/profile/addresses', [CustomerApiController::class, 'getCustomerAddresses']);
        Route::put('{store}/profile/addresses', [CustomerApiController::class, 'updateCustomerAddress']);
        Route::delete('{store}/profile/addresses', [CustomerApiController::class, 'destroyCustomerAddress']);
        Route::post('{store}/profile/addresses', [CustomerApiController::class, 'storeCustomerAddress']);

        Route::get('{store}/profile/authors', [CustomerApiController::class, 'getCustomerAuthors']);
        Route::delete('{store}/profile/authors', [CustomerApiController::class, 'destroyCustomerAuthor']);
        Route::post('{store}/profile/authors', [CustomerApiController::class, 'storeCustomerAuthor']);
        Route::post('{store}/profile/author-follow-up', [CustomerApiController::class, 'toggleAuthorFollowUp']);

        Route::get('{store}/profile/reviews', [CustomerApiController::class, 'getCustomerReviews']);
        Route::delete('{store}/profile/reviews', [CustomerApiController::class, 'destroyCustomerReview']);
        Route::post('{store}/profile/reviews', [CustomerApiController::class, 'storeCustomerReview']);
        Route::put('{store}/profile/reviews', [CustomerApiController::class, 'updateCustomerReview']);

        Route::get('{store}/profile/wishlist', [CustomerApiController::class, 'getCustomerWishlist']);
        Route::delete('{store}/profile/wishlist', [CustomerApiController::class, 'destroyCustomerWish']);
        Route::post('{store}/profile/wishlist', [CustomerApiController::class, 'storeCustomerWish']);

        Route::get('{store}/profile/affiliate-info', [CustomerApiController::class, 'getCustomerAffiliateInfo']);
        Route::post('{store}/profile/affiliate-info', [CustomerApiController::class, 'updateCustomerAffiliateInfo']);
        Route::get('{store}/profile/affiliate-redeem', [CustomerApiController::class, 'customerRedeemBalance']);
        Route::get('{store}/profile/affiliate-redeems', [CustomerApiController::class, 'getCustomerRedeems']);

        // Comments
        Route::delete('{store}/comments', [CommentApiController::class, 'destroy']);
        Route::post('{store}/comments', [CommentApiController::class, 'store']);
        Route::put('{store}/comments', [CommentApiController::class, 'update']);
    });
});
