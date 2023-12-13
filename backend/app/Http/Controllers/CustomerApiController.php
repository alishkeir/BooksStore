<?php

namespace App\Http\Controllers;

use Alomgyar\Affiliates\AffiliateRedeem;
use Alomgyar\Authors\Author;
use Alomgyar\Countries\Country;
use Alomgyar\Customers\Address;
use Alomgyar\Customers\Customer;
use Alomgyar\Customers\CustomerAuthor;
use Alomgyar\Settings\Settings;
use App\Http\Resources\AffiliateBalanceResource;
use App\Http\Resources\AffiliateInfoResource;
use App\Http\Resources\AffiliateRedeemsResource;
use App\Http\Resources\CustomerAuthorResource;
use App\Http\Resources\ReviewsResource;
use Alomgyar\Products\Product;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\AddressesResource;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerPreorderResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerReviewsResource;
use App\Http\Resources\EbookListResource;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\PersonalDetailsResource;
use App\Http\Resources\ProductListResource;
use App\Http\Traits\ErrorMessages;
use App\Order;
use App\OrderItem;
use App\Services\AffiliateService;
use App\Services\GeneratePdfService;
use App\User;
use App\PreOrder;
use App\PublicPreOrder;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerApiController extends Controller
{
    use ErrorMessages;

    protected array $validRefs = [
        'personalDetails',
        'profileUpdate',
        'customerOrders',
        'customerPreOrders',
        'customerAddresses',
        'customerAuthors',
        'customerEbooks',
        'customerReviews',
        'customerWishlist',
        'downloadEbook',
        'downloadInvoice',
    ];

    private int $perPage;

    private int $page;

    public function __construct()
    {
        $this->refCheck();
        $this->perPage = 20;
        $this->page = request()->body['page'] ?? 1;
    }

    private function refCheck()
    {
        if (! in_array(request('ref'), $this->validRefs) && request()->expectsJson()) {
            return $this->badRefMessage();
        }
    }

    public function getPersonalDetails()
    {
        return response(['data' => new PersonalDetailsResource(request()->user())]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $validated = $request->validated();
        if ($request->user()->update($validated)) {
            return response([
                'data' => [
                    'token' => $request->user()->createToken('auth')->plainTextToken,
                    'valid_until' => Customer::tokenValidUntil(),
                    'customer' => new CustomerResource($request->user()),
                ],
            ]);
        }
    }

    public function getCustomerOrders()
    {
        $orders = request()->user()
            ->orders()
            ->where('orders.status', '>', Order::STATUS_DRAFT)
            ->where('orders.status', '!=', Order::STATUS_DELETED)
            ->paginate($this->perPage, ['*'], 'page', $this->page);

        return response([
            'data' => [
                'orders' => OrdersResource::collection($orders),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'last_page' => $orders->currentPage() === $orders->lastPage(),
                ],
            ],
        ]);
    }

    public function getCustomerEbooks()
    {
        $ebooks = request()->user()->ebooks;
        $count = $ebooks->count();
        $ebooks = $ebooks->skip($this->perPage * ($this->page - 1))
            ->take($this->perPage);

        return response([
            'data' => [
                'ebooks' => EbookListResource::collection($ebooks),
                'pagination' => [
                    'current_page' => $this->page,
                    'per_page' => $this->perPage,
                    'total' => $count,
                    'last_page' => $count <= $this->page * $this->perPage,
                ],
            ],
        ]);
    }

    public function getDownloadInvoice()
    {
        if (isset(request()->body['id'])) {
            $order = Order::where([['id', request()->body['id']], ['customer_id', request()->user()->id]])->first();

            if (! empty($order) && ! empty($order->invoice_url)) {
                $file = app_path().'/Components/Szamlazz/pdf/'.$order->invoice_url.'.pdf';

                if (is_file($file)) {
                    header('Access-Control-Allow-Origin: *');
                    //header('Content-Type: application/octet-stream');
                    header('Access-Control-Allow-Methods: GET, POST');
                    header('Access-Control-Allow-Headers: X-Requested-With');
                    header('Content-Description: File Transfer');
                    //header("Access-Control-Expose-Headers: Content-Disposition");
                    header('Content-Type: application/pdf');
                    header('Content-Length:'.filesize($file));
                    header('Content-Disposition: attachment; filename="'.$order->invoice_url.'.pdf"');
                    readfile($file);
                    exit();
                }
            }
        }
    }

    public function getDownloadEbook(Request $request)
    {
        if (request('ref') == 'downloadInvoice') {
            return $this->getDownloadInvoice();
        }

        $params = $request->all();

        if (! isset($request->bookId)) {
            $bookId = request()->body['ebook_id'];
        } else {
            $bookId = $request->bookId;
        }
        if (isset($params['admin'])) {
            $findBook = Product::where([['id', $bookId]])->first();
            $book = $findBook;
            $title = $findBook->title;
        } else {
            $findBook = OrderItem::with('product')
                ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->where([
                    ['orders.customer_id', request()->user()->id],
                    ['order_items.product_id', $bookId],
                ])
                ->whereNotIn('status', [Order::STATUS_DRAFT, Order::STATUS_DELETED])
                ->where('payment_status', Order::STATUS_PAYMENT_PAID)
                ->first();

            $book = $findBook->product;
            $title = $findBook->product->title;
        }

        if (empty($findBook)) {
            return false;
        }

        $type = request()->body['type'] ?? 'epub';

        if ($book->type !== Product::EBOOK) {
            return false;
        }

        if (empty($book->dibook_id)) {
            return false;
        }

        //$url = "http://api.dibook.hu/alomgyar/index.php?productid=".$sku."&userid=".$this->usermanagment_model->user->user_id."&format=".($type == "epub" ? "epub" : "mobi")."&transaction=".$p->order_code;
        $url = 'https://dibook.hu/api/book/download?productid='.$book->dibook_id.'&userid=&format='.($type == 'epub' ? 'epub' : 'mobi').'&token=AL7744';
        //$url = "https://dibook.skvad.app/api/book/download?productid=".$findBook->product->dibook_id."&userid=&format=".($type == "epub" ? "epub" : "mobi")."&token=AL7744";

        try {
            set_time_limit(0);
            ini_set('max_execution_time', 120);
            ini_set('default_socket_timeout', 120);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_BUFFERSIZE, 256);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 960);

            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            header('Access-Control-Allow-Headers: X-Requested-With');
            header('Content-Description: File Transfer');
            header('Access-Control-Expose-Headers: Content-Disposition');
            header('Content-type: application/'.($type == 'epub' ? 'epub' : 'mobi').'+zip');
            header('Content-Disposition: attachment; filename="'.Str::slug(
                $title,
                '-'
            ).'.'.($type == 'epub' ? 'epub' : 'mobi').'"');

            $res = curl_exec($ch);
            curl_close($ch);

            //header('Content-Length: '.strlen($res));
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        exit();
    }

    public function getCustomerPreOrders()
    {
        return $this->preOrderResponse();
    }

    public function destroyCustomerPreOrder()
    {
        if (empty(request()->body['product_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $preOrder = DB::table('customer_preorders')->where([
            ['product_id', request()->body['product_id']],
            ['customer_id', request()->user()->id],
        ]);

        if ($preOrder->doesntExist()) {
            return $this->notFoundMessage();
        }

        $preOrder->delete();

        return $this->preOrderResponse();
    }

    public function storeCustomerPreOrder()
    {
        if (empty(request()->body['product_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $validator = Validator::make(request()->body, [
            'product_id' => [
                Rule::unique('customer_preorders')->where(function ($query) {
                    return $query->where('customer_id', request()->user()->id)
                        ->where('product_id', request()->body['product_id']);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        PreOrder::create([
            'customer_id' => request()->user()->id,
            'product_id' => request()->body['product_id'],
        ]);

        return $this->preOrderResponse();
    }

    public function storePublicPreOrder()
    {
        if (empty(request()->body['product_id']) || empty(request()->body['email'])) {
            return $this->missingRequiredParameterMessage();
        }

        // check email is registered
        $customer = Customer::query()->firstWhere('email', request()->body['email']);

        // IF THE EMAIL ADDRESS BELONGS TO A CUStoMEr
        if ($customer) {
            $validator = Validator::make(request()->body, [
                'product_id' => [
                    Rule::unique('customer_preorders')->where(function ($query) use ($customer) {
                        return $query->where('customer_id', $customer->id)
                            ->where('product_id', request()->body['product_id']);
                    }),
                ],
            ]);

            if (! $validator->fails()) {
                PreOrder::create([
                    'customer_id' => $customer->id,
                    'product_id' => request()->body['product_id'],
                ]);
            }
            // IF THE EMAIL ADDRESS IS TOTALLY NEW
        } else {
            $validator = Validator::make(request()->body, [
                'product_id' => [
                    Rule::unique('public_preorders')->where(function ($query) {
                        return $query->where('email', request()->body['email'])
                            ->where('product_id', request()->body['product_id']);
                    }),
                ],
            ]);

            if (! $validator->fails()) {
                PublicPreOrder::create([
                    'email' => request()->body['email'],
                    'product_id' => request()->body['product_id'],
                    'store' => request('store'),
                ]);
            }
        }

        return response([
            'data' => [
                'success' => true,
            ],
        ]);
    }

    private function preOrderResponse()
    {
        $preorders = request()->user()->preorders()->paginate($this->perPage, ['*'], 'page', $this->page);

        if (isset(request()->body['only_id']) && request()->body['only_id']) {
            return response([
                'data' => [
                    'preorder_items' => $preorders->isNotEmpty() ? CustomerPreorderResource::collection($preorders) : [],
                ],
            ]);
        }

        return response([
            'data' => [
                'preorder_items' => $preorders->isNotEmpty() ? ProductListResource::collection($preorders) : [],
                'pagination' => [
                    'current_page' => $preorders->currentPage(),
                    'per_page' => $preorders->perPage(),
                    'total' => $preorders->total(),
                    'last_page' => $preorders->currentPage() === $preorders->lastPage(),
                ],
            ],
        ]);
    }

    public function getCustomerAddresses()
    {
        if (empty(request()->body['type'])) {
            return $this->missingRequiredParameterMessage();
        }

        return response([
            'data' => [
                'addresses' => AddressesResource::collection(request()->body['type'] === 'billing'
                    ? request()->user()->billingAddresses
                    : request()->user()->shippingAddresses),
                'countries' => CountryResource::collection(Country::active()->get()),
            ],
        ]);
    }

    public function updateCustomerAddress()
    {
        if (empty(request()->body['address_id']) || empty(request()->body['type'])) {
            return $this->missingRequiredParameterMessage();
        }

        $address = Address::find(request()->body['address_id']);

        if ($address->role_id !== request()->user()->id) {
            return $this->authFailedMessage();
        }

        $validator = $this->addressValidator();

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }
        $validatedData = $validator->validated();

        $validatedData['entity_type'] === 'private'
            ? $validatedData['entity_type'] = Address::ENTITY_PRIVATE
            : $validatedData['entity_type'] = Address::ENTITY_BUSINESS;

        $address->update($validatedData);

        return response([
            'data' => [
                'addresses' => AddressesResource::collection(request()->body['type'] === 'billing'
                    ? request()->user()->billingAddresses
                    : request()->user()->shippingAddresses),
                'countries' => CountryResource::collection(Country::active()->get()),
            ],
        ]);
    }

    public function storeCustomerAddress()
    {
        if (empty(request()->body['type'])) {
            return $this->missingRequiredParameterMessage();
        }

        $validator = $this->addressValidator();

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        $validatedData = $validator->validated();

        $validatedData['entity_type'] === 'private'
            ? $validatedData['entity_type'] = Address::ENTITY_PRIVATE
            : $validatedData['entity_type'] = Address::ENTITY_BUSINESS;

        $validatedData['role_id'] = request()->user()->id;

        Address::create($validatedData);

        return response([
            'data' => [
                'addresses' => AddressesResource::collection(request()->body['type'] === 'billing'
                    ? request()->user()->billingAddresses
                    : request()->user()->shippingAddresses),
                'countries' => CountryResource::collection(Country::active()->get()),
            ],
        ]);
    }

    public function destroyCustomerAddress()
    {
        if (empty(request()->body['address_id']) || empty(request()->body['type'])) {
            return $this->missingRequiredParameterMessage();
        }

        $address = Address::find(request()->body['address_id']);

        if ($address->role_id !== request()->user()->id) {
            return $this->authFailedMessage();
        }

        $address->delete();

        return response([
            'data' => [
                'addresses' => AddressesResource::collection(request()->body['type'] === 'billing'
                    ? request()->user()->billingAddresses
                    : request()->user()->shippingAddresses),
                'countries' => CountryResource::collection(Country::active()->get()),
            ],
        ]);
    }

    private function addressValidator()
    {
        $validator = Validator::make(request()->body, Address::$validationRules, Address::$validationMessages);

        $validator->after(function ($validator) {
            if (
                (empty(request()->body['last_name']) || empty(request()->body['first_name']))
                && empty(request()->body['business_name'])
                && request()->body['type'] === 'shipping'
            ) {
                $validator->errors()->add(
                    'common',
                    'Vagy a vezetéknév + keresztnév, vagy a cég név kötelező'
                );
            }

            $addressCount = Address::where([
                ['type', request()->body['type']],
                ['role', 'customer'],
                ['role_id', request()->user()->id],
            ])->count();
            if ($addressCount >= 5) {
                $validator->errors()->add(
                    'common',
                    'Elérted a maximálisan elmenthető címek számát'
                );
            }
        });

        return $validator;
    }

    public function getCustomerAuthors()
    {
        return $this->authorResponse();
    }

    public function destroyCustomerAuthor()
    {
        if (empty(request()->body['author_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $author = DB::table('customer_authors')->where([
            ['author_id', request()->body['author_id']],
            ['customer_id', request()->user()->id],
        ]);

        if ($author->doesntExist()) {
            return $this->notFoundMessage();
        }

        $author->delete();

        return $this->authorResponse();
    }

    public function storeCustomerAuthor()
    {
        if (empty(request()->body['author_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $validator = Validator::make(request()->body, [
            'author_id' => [
                Rule::unique('customer_authors')->where(function ($query) {
                    return $query->where('customer_id', request()->user()->id)
                        ->where('author_id', request()->body['author_id']);
                }),
            ],
        ], [
            'author_id.unique' => 'Erre a szerzőre már feliratkoztál',
        ]);

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        CustomerAuthor::create([
            'customer_id' => request()->user()->id,
            'author_id' => request()->body['author_id'],
        ]);

        return $this->authorResponse();
    }

    public function toggleAuthorFollowUp()
    {
        $user = request()->user();

        $user->update([
            'author_follow_up' => $user->author_follow_up == 1 ? 0 : 1,
        ]);

        return response([
            'data' => [
                'customer' => new CustomerResource($user),
            ],
        ]);
    }

    private function authorResponse()
    {
        $authors = request()->user()->authors()->paginate($this->perPage, ['*'], 'page', $this->page);

        if (isset(request()->body['only_id']) && request()->body['only_id']) {
            return response([
                'data' => [
                    'authors' => $authors->isNotEmpty() ? CustomerAuthorResource::collection($authors) : [],
                ],
            ]);
        }

        return response([
            'data' => [
                'author_follow_up' => request()->user()->author_follow_up,
                'authors' => $authors->isNotEmpty() ? AuthorResource::collection($authors) : [],
                'pagination' => [
                    'current_page' => $authors->currentPage(),
                    'per_page' => $authors->perPage(),
                    'total' => $authors->total(),
                    'last_page' => $authors->currentPage() === $authors->lastPage(),
                ],
            ],
        ]);
    }

    public function getCustomerReviews()
    {
        return $this->reviewResponse();
    }

    public function destroyCustomerReview()
    {
        if (empty(request()->body['product_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $review = DB::table('product_review')->where([
            ['product_id', request()->body['product_id']],
            ['customer_id', request()->user()->id],
        ]);

        if ($review->doesntExist()) {
            return $this->authFailedMessage();
        }

        $review->delete();

        return $this->reviewResponse();
    }

    public function storeCustomerReview()
    {
        if (empty(request()->body['product_id'])) {
            return $this->missingRequiredParameterMessage();
        }
        if (! Product::find(request()->body['product_id'])) {
            return false;
        }
        $validator = Validator::make(request()->body, [
            'product_id' => [
                Rule::unique('product_review')->where(function ($query) {
                    return $query->where('customer_id', request()->user()->id)
                        ->where('product_id', request()->body['product_id']);
                }),
            ],
            'review' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        DB::table('product_review')->insert([
            'customer_id' => request()->user()->id,
            'product_id' => request()->body['product_id'],
            'review' => request()->body['review'],
            'store' => request('store'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->reviewResponse();
    }

    public function updateCustomerReview()
    {
        if (empty(request()->body['product_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $validator = Validator::make(request()->body, [
            'review' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        $review = DB::table('product_review')->where([
            ['product_id', request()->body['product_id']],
            ['customer_id', request()->user()->id],
        ]);

        if (empty($review->first())) {
            return $this->notFoundMessage();
        }

        $review->update([
            'product_id' => request()->body['product_id'],
            'review' => request()->body['review'],
            'store' => request('store'),
            'updated_at' => now(),
        ]);

        return $this->reviewResponse();
    }

    private function reviewResponse()
    {
        $reviews = request()->user()->reviews()->paginate($this->perPage, ['*'], 'page', $this->page);

        if (isset(request()->body['only_id']) && request()->body['only_id']) {
            return response([
                'data' => [
                    'reviews' => $reviews->isNotEmpty() ? CustomerReviewsResource::collection($reviews) : [],
                ],
            ]);
        }

        return response([
            'data' => [
                'reviews' => ReviewsResource::collection($reviews),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'last_page' => $reviews->currentPage() === $reviews->lastPage(),
                ],
            ],
        ]);
    }

    public function getCustomerWishlist()
    {
        return $this->wishlistResponse();
    }

    public function destroyCustomerWish()
    {
        if (empty(request()->body['product_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $wish = DB::table('customer_wishlist')->where([
            ['product_id', request()->body['product_id']],
            ['customer_id', request()->user()->id],
        ]);

        if ($wish->doesntExist()) {
            return $this->authFailedMessage();
        }

        $wish->delete();

        return $this->wishlistResponse();
    }

    public function storeCustomerWish()
    {
        if (empty(request()->body['product_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $validator = Validator::make(request()->body, [
            'product_id' => [
                Rule::unique('customer_wishlist')->where(function ($query) {
                    return $query->where('customer_id', request()->user()->id)
                        ->where('product_id', request()->body['product_id']);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        DB::table('customer_wishlist')->insert([
            'customer_id' => request()->user()->id,
            'product_id' => request()->body['product_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->wishlistResponse();
    }

    private function wishlistResponse()
    {
        $wishlist = request()->user()->wishlist()->latest()->paginate($this->perPage, ['*'], 'page', $this->page);

        if (isset(request()->body['only_id']) && request()->body['only_id']) {
            return response([
                'data' => [
                    'wishlist' => $wishlist->isNotEmpty() ? CustomerReviewsResource::collection($wishlist) : [],
                ],
            ]);
        }

        return response([
            'data' => [
                'wishlist' => ProductListResource::collection($wishlist),
                'pagination' => [
                    'current_page' => $wishlist->currentPage(),
                    'per_page' => $wishlist->perPage(),
                    'total' => $wishlist->total(),
                    'last_page' => $wishlist->currentPage() === $wishlist->lastPage(),
                ],
            ],
        ]);
    }

    public function getCustomerAffiliateInfo()
    {
        return response(['data' => new AffiliateInfoResource(request()->user())]);
    }
    public function updateCustomerAffiliateInfo()
    {
        $user = request()->user();
        $validator = Validator::make(request()->body, [
            'name' => 'required',
            'country' => 'required',
            'zip' => 'required|numeric',
            'city' => 'required',
            'address' => 'required',
            'vat' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }
        if ($user && $user->is_affiliate) {
            $user->affiliate()->update(request()->body);
            $user->refresh();
        }
        return response(['data' => new AffiliateInfoResource(request()->user())]);
    }
    public function customerRedeemBalance()
    {
        $customer = request()->user();
        $currentBalance = (new AffiliateService)->getCustomerBalance($customer);
        $minimumRedeemAmount = Cache::rememberForever('minimum_redeem_amount', function () {
            return Settings::where('key', 'minimum_redeem_amount')->first()?->primary ?? 100000;
        });
        if ($currentBalance >= $minimumRedeemAmount) {
            $affiliateRedeem = AffiliateRedeem::create([
                'amount' => $currentBalance,
                'customer_id' => $customer->id
            ]);
            return response(['data' => new AffiliateBalanceResource(request()->user())]);
        } else {
            return $this->validatorErrorMessage(['redeem_balance_error' => true]);
        }
    }
    public function getCustomerRedeems()
    {
        return response(['data' => AffiliateRedeemsResource::collection(AffiliateRedeem::where('customer_id', request()->user()->id)->get())]);
    }
}
