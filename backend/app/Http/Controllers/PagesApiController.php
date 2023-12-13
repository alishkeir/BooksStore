<?php

namespace App\Http\Controllers;

use Alomgyar\Posts\ApiPost as Post;
use Alomgyar\Products\ApiProduct as Product;
use Alomgyar\Promotions\ApiPromotion as Promotion;
use Alomgyar\Shops\Shop;
use App\Http\Resources\PostListResource;
use App\Http\Resources\PostShowResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\PromotionListResource;
use App\Http\Resources\PromotionResource;
use App\Http\Resources\ShopResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PagesApiController extends Controller
{
    use ErrorMessages;

    protected array $validRefs = ['list', 'show'];

    protected array $validSlugs = ['promotions', 'posts', 'shops'];

    protected array $titles = [];

    protected int $page;

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        if (! in_array(request('ref'), $this->validRefs) || ! in_array(request('slug'), $this->validSlugs)) {
            return $this->badRefMessage();
        }

        $this->titles = config('pam.titles') ?? [];
        $this->page = request()->body['page'] ?? 1;

        if (request('slug') === 'promotions') {
            return $this->getPromotions();
        }

        if (request('slug') === 'posts' && ! isset(request()->body['slug'])) {
            return $this->getPosts();
        }

        if (request('slug') === 'posts' && isset(request()->body['slug'])) {
            return $this->getSinglePost();
        }

        if (request('slug') === 'shops') {
            return $this->getShops();
        }

        throw new BadRequestHttpException('Hogy kerültél ide báttya?');
    }

    private function getPromotions()
    {
        $promotions = Promotion::active()->byStore()->orderBy('order')->get();
        $products = Product::promotionalSuccessList();

        return [
            'data' => [
                'page_title' => $this->titles[request('slug')] ?? null,
                'promotions' => PromotionListResource::collection($promotions),
                'products' => ProductListResource::collection($products),
            ],
        ];
    }

    private function getPosts()
    {
        $posts = Post::active()
                     ->byStore()
                     ->search(trim(request()->body['filters']['search'] ?? null))
                     ->when(isset(request()->body['filters']['year']), function ($query) {
                         return $query->whereYear('published_at', request()->body['filters']['year']);
                     })
                     ->when(isset(request()->body['filters']['month']) && isset(request()->body['filters']['year']),
                         function ($query) {
                             return $query->whereMonth('published_at', request()->body['filters']['month']);
                         })
                     ->orderBy('published_at', 'DESC')
                     ->paginate(Post::PER_PAGE, ['*'], 'page', $this->page);

        $availableYears = DB::table('posts')
                            ->selectRaw('DISTINCT YEAR(published_at) as year')
                            ->orderBy('year')
                            ->get()
                            ->transform(function ($item) {
                                $item->selected = false;
                                if ($item->year == request()->body['filters']['year']) {
                                    $item->selected = true;
                                }

                                return $item;
                            });

        $availableMonths = isset(request()->body['filters']['year'])
            ? DB::table('posts')->selectRaw('DISTINCT MONTH(published_at) as month')->whereYear('published_at',
                request()->body['filters']['year'])->orderBy('month')->get()->transform(function ($item) {
                    $item->selected = false;
                    if ($item->month == request()->body['filters']['month']) {
                        $item->selected = true;
                    }

                    return $item;
                })
            : [];

        return [
            'data' => [
                'page_title' => $this->titles[request('slug')] ?? null,
                'available_years' => $availableYears,
                'available_months' => $availableMonths,
                'filters' => [
                    'search' => request()->body['filters']['search'] ?? null,
                    'year' => request()->body['filters']['year'] ?? null,
                    'month' => request()->body['filters']['month'] ?? null,
                ],
                'page' => $this->page,
                'posts' => PostListResource::collection($posts),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    'last_page' => $posts->currentPage() === $posts->lastPage(),
                ],
            ],
        ];
    }

    private function getSinglePost()
    {
        $post = Post::active()->byStore()->whereSlug(request()->body['slug'])->first();

        if (empty($post)) {
            return $this->notFoundMessage();
        }

        return ['data' => new PostShowResource($post)];
    }

    private function getShops()
    {
        $bookShops = Shop::active()->where('store_'.request('store'), Shop::STATUS_ACTIVE)->orderBy('title')->get();
        $newBookShops = Shop::active()->latest()->orderBy('title')->limit(3)->get();
        $promotion = Promotion::active()->byStore()->get();

        return [
            'data' => [
                'page_title' => $this->titles[request('slug')] ?? null,
                'notification' => $this->getNotification(),
                'book_shops' => ShopResource::collection($bookShops),
                'new_book_shops' => ShopResource::collection(empty($promotion) ? $newBookShops : $newBookShops->take(2)),
                'promotion' => count($promotion) ? new PromotionResource($promotion->random()) : null,
            ],
        ];
    }

    private function getNotification()
    {
        return match ((int) request('store')) {
            0 => option('notification_title_alomgyar') && option('notification_description_alomgyar') ? [
                'title' => option('notification_title_alomgyar'),
                'message' => option('notification_description_alomgyar'),
            ] : null,
            1 => option('notification_title_olcsokonyvek') && option('notification_description_olcsokonyvek') ? [
                'title' => option('notification_title_olcsokonyvek'),
                'message' => option('notification_description_olcsokonyvek'),
            ] : null,
            2 => option('notification_title_nagyker') && option('notification_description_nagyker') ? [
                'title' => option('notification_title_nagyker'),
                'message' => option('notification_description_nagyker'),
            ] : null,
        };
    }
}
