<?php

namespace App\Http\Controllers;

use Alomgyar\Authors\ApiAuthor as Author;
use Alomgyar\Categories\ApiCategory as Category;
use Alomgyar\Customers\Customer;
use Alomgyar\Products\ApiProduct as Product;
use Alomgyar\Promotions\ApiPromotion as Promotion;
use Alomgyar\RankedProducts\Model\RankedProduct;
use Alomgyar\Subcategories\ApiSubcategory as Subcategory;
use App\Http\Resources\AuthorShowResource;
use App\Http\Resources\CustomerAuthorShowResource;
use App\Http\Resources\CustomerProductResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductShowResource;
use App\Http\Resources\PromotionResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class ProductApiController extends Controller
{
    use ErrorMessages;

    protected array $titles = [];

    protected array $validRefs = ['list', 'show', 'subcategoriesByCategory'];

    private array $params;

    private mixed $section;

    private mixed $byPublishing;

    private mixed $category;

    private mixed $subcategory;

    private string $sortBy;

    private int $page;

    private bool $is_auth;

    private Customer $customer;

    public function __invoke()
    {
        if (! in_array(request('ref'), $this->validRefs)) {
            return $this->badRefMessage();
        }

        $this->titles = config('pam.titles');
        $this->params = request()->all()['body'];
        $this->section = $this->params['section'] ?? null;
        $this->byPublishing = isset($this->params['filters']) ? $this->params['filters']['by_publishing'] : null;
        $this->sortBy = $this->params['sort_by'] ?? 'most-popular';
        $this->page = $this->params['page'] ?? 1;
        $this->is_auth = $this->params['customer'] ?? false;
        if ($this->is_auth) {
            if ($token = request()->bearerToken()) {
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($token);
                if (empty($accessToken)) {
                    return $this->authFailedMessage();
                }
                $this->customer = $accessToken->tokenable;
            }
        }

        if (request('ref') === 'list') {
            return $this->getProductList();
        } elseif (request('ref') === 'show') {
            return $this->getSingleProduct();
        } else {
            return $this->getSubcategoriesByCategory();
        }
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    private function getProductList()
    {
        $this->setCategoriesAndSubcategories();

        if ($this->params['section'] === 'author' && ! isset($this->params['section_params']['slug'])) {
            return $this->missingRequiredParameterMessage();
        } elseif ($this->params['section'] === 'author' && isset($this->params['section_params']['slug'])) {
            return $this->getSingleAuthor();
        }

        $products = Product::active();

        //Promotion
        if ($this->params['section'] === 'promotion' && ! isset($this->params['section_params']['slug'])) {
            return $this->missingRequiredParameterMessage();
        } elseif ($this->params['section'] === 'promotion' && isset($this->params['section_params']['slug'])) {
            $promotion = Promotion::active()->byStore()->whereSlug($this->params['section_params']['slug'])->first();

            if (empty($promotion)) {
                return $this->notFoundMessage();
            }

            $products = $promotion->products();
            $products = $this->productsQuery($products);
        }

        //Ranked full lists
        if ($this->params['section'] === 'ranked' && ! isset($this->params['section_params']['slug'])) {
            return $this->missingRequiredParameterMessage();
        }

        if ($this->params['section'] === 'ranked' && isset($this->params['section_params']['slug'])) {
            $type = $this->mapType($this->params['section_params']['slug']);
            $this->titles['ranked'] = $this->mapRankedPageTitle($this->params['section_params']['slug']);

            $storeId = request('store');

            $productIds = RankedProduct::where('type', $type)
                                       ->where('store_id', $storeId)
                                       ->orderBy('rank', 'ASC')
                                       ->get()->pluck('product_id')->toArray();

            $products = Product::join('ranked_products', 'product.id', '=', 'ranked_products.product_id')
                               ->select('product.id', 'product.title', 'product.slug', 'product.status',
                                   'product.cover', 'product.state', 'product.type', 'product.authors',
                                   'ranked_products.rank', 'product.is_new', 'product.publisher_id')
                               ->thisStore()
                               ->whereIn('product.id', $productIds)
                               ->where('ranked_products.type', $type)
                               ->where('product.status', '!=', 0)
                               ->where('store_id', $storeId)
                               ->with('ranked')
                               ->orderBy('ranked_products.rank', 'ASC');
        }
        //Books/Ebooks
        if ($this->params['section'] === 'book' || $this->params['section'] === 'ebook') {
            $products = $products->whereType($this->params['section'] === 'book' ? Product::BOOK : Product::EBOOK);
        }

        if ($this->params['section'] === 'ujdonsagok') {
            $products = $products
                ->orderBy('release_year', 'DESC')
                ->orderBy('id', 'DESC');
        }

        if ($this->params['section'] !== 'ranked' && ! isset($this->params['section_params']['slug'])) {
            $products = $this->productsQuery($products);
        }

        $count = $products->count();
        $products = $products->offset(Product::PER_PAGE * ($this->page - 1))
                             ->limit(Product::PER_PAGE)
                             ->get();

        if (request('store') == 2) {
            $this->isLoggedIn();
            if (! empty($this->customer)) {
                $products->customer = $this->customer;
            }
        }
        $response = [
            'data' => [
                'section' => $this->section,
                'section_params' => $this->params['section_params'],
                'page_title' => $this->titles[$this->section] ?? (isset($promotion) ? $promotion->title : null),
                'sort_by' => Product::getSortBy($this->sortBy),
                'filters' => [
                    Product::getByPublishing($this->byPublishing),
                    Product::getCategory(),
                    Product::getSubcategory(),
                ],
                'products' => ProductListResource::collection($products),
                'pagination' => [
                    'current_page' => $this->page,
                    'per_page' => Product::PER_PAGE,
                    'total' => $count,
                    'last_page' => $count <= $this->page * Product::PER_PAGE,
                ],
            ],
        ];

        if (isset($promotion)) {
            $response['data']['promotion'] = new PromotionResource($promotion);
        }

        return response($response);
    }

    private function productsQuery($products)
    {
        return $products->select('product.id', 'product.slug', 'product.title', 'product.state', 'product.type',
            'product.cover', 'product.is_new', 'product.publisher_id')
                        ->with([
                            'prices' => function ($query) {
                                $query->where('store', request('store'));
                            },
                        ])
                        ->join('product_price', 'product.id', '=', 'product_price.product_id')
                        ->where('product_price.store', request('store'))
                        ->where('product_price.price_sale', '>', 0)
                        ->active()
                        ->thisStore()
                        ->when($this->category, function ($query) {
                            $products = [];
                            foreach ($this->category->subcategories ?? [] as $subcategory) {
                                $products[] = $subcategory->products->isNotEmpty() ? $subcategory->products->pluck('id') : null;
                            }
                            $products = collect($products)->flatten()->values()->all();

                            return $query->whereIn('product.id', $products);
                        })
                        ->when($this->subcategory, function ($query) {
                            return $query->whereHas('subcategories', function ($q) {
                                return $q->whereIn('subcategory_id', $this->subcategory->pluck('id'));
                            });
                        })
                        ->when($this->byPublishing && in_array('normal', $this->byPublishing),
                            function ($query) {
                                return $query->where('product.state', Product::STATE_NORMAL);
                            })
                        ->when(is_array($this->byPublishing), function ($query) {
                            // Csak az ideiek: Ha igen, SZŰKÍTI a listát csak idén megjelentekkel
                            // Csak az akciósak: Ha igen SZŰKÍTI a listát csak az akciós könyvekre
                            if (in_array('csak-az-ideiek', $this->byPublishing)) {
                                $query->whereReleaseYear(date('Y'));
                            }
                            if (in_array('csak-az-akciosak', $this->byPublishing)) {
                                $query->where('product_price.discount_percent', '>', 0);
                            }

                            return $query;
                        })
                        ->when($this->sortBy === 'most-popular', function ($query) {
                            return $query->orderBy('product.orders_count_'.request('store'), 'DESC');
                        })
                        ->when($this->sortBy === 'release-year', function ($query) {
                            return $query->orderBy('product.release_year', 'DESC');
                        })
                        ->when($this->sortBy === 'price-asc', function ($query) {
                            return $query->orderBy('product_price.price_sale', 'ASC');
                        })
                        ->when($this->sortBy === 'price-desc', function ($query) {
                            return $query->orderBy('product_price.price_sale', 'DESC');
                        })
                        ->when($this->sortBy === 'biggest-discount', function ($query) {
                            return $query->orderBy('product_price.discount_percent', 'DESC')->orderBy('product.title',
                                'ASC');
                        });
    }

    private function getSingleProduct(): Application|ResponseFactory|Response
    {
        if (! isset($this->params['slug'])) {
            return $this->missingRequiredParameterMessage();
        }
        $slug = $this->params['slug'];

        $product = Product::active()
                            ->select('product.*', 'product_price.price_list', 'product_price.price_sale', 'product_price.discount_percent')
                          ->with(['author', 'subcategories', 'prices'])
                          ->join('product_price', function ($join) {
                              $join->on('product_price.product_id', 'product.id')
                                   ->where('store', request('store'));
                          })
                          ->where('product_price.price_sale', '>', 0)
                          ->whereSlug($slug)->thisStore()->first();

        if (empty($product)) {
            return $this->notFoundMessage();
        }

        if ($this->is_auth && ! empty($this->customer)) {
            $product->customer = $this->customer;
        } else {
            $this->isLoggedIn();
            if (! empty($this->customer)) {
                $product->customer = $this->customer;
            }
        }

        return $this->is_auth && ! empty($this->customer)
            ? response(['data' => new CustomerProductResource($product)])
            : response(['data' => new ProductShowResource($product)]);
    }

    private function getSingleAuthor()
    {
        if (! isset($this->params['section_params']['slug'])) {
            return $this->missingRequiredParameterMessage();
        }

        $author = Author::active()->with('products')->whereSlug($this->params['section_params']['slug'])->first();

        if (empty($author)) {
            return $this->notFoundMessage();
        }

        if ($this->is_auth && ! empty($this->customer)) {
            $author->customer = $this->customer;
        }

        return $this->is_auth && ! empty($this->customer)
            ? response(['data' => new CustomerAuthorShowResource($author)])
            : response(['data' => new AuthorShowResource($author)]);
    }

    private function getSubcategoriesByCategory(): Response|Application|ResponseFactory
    {
        $this->setCategoriesAndSubcategories();

        return response([
            'data' => [
                'section' => $this->section,
                'section_params' => $this->params['section_params'],
                'page_title' => $this->titles[$this->section] ?? null,
                'filters' => [
                    Product::getByPublishing($this->byPublishing),
                    Product::getCategory(),
                    Product::getSubcategory(),
                ],
            ],
        ]);
    }

    private function setCategoriesAndSubcategories()
    {
        if ((! isset($this->params['filters']['category']) && ! isset($this->params['filters']['subcategory'])) || (! isset($this->params['filters']['category']) && isset($this->params['filters']['subcategory']))) {
            $this->category = $this->subcategory = null;
        } elseif (isset($this->params['filters']['category']) && ! isset($this->params['filters']['subcategory'])) {
            $this->category = Category::active()->whereSlug($this->params['filters']['category'])->first();
            $this->subcategory = $this->category?->subcategories ?? [];
        } else {
            $this->category = Category::active()->whereSlug($this->params['filters']['category'])->first();
            $this->subcategory = Subcategory::active()->whereSlug($this->params['filters']['subcategory'])->get();
        }
    }

    private function mapType(string $slug): string
    {
        return match ($slug) {
            'eladasi-sikerlista' => 'sold',
            'akcios-sikerlista' => 'discount_sold',
            'elojegyzes-sikerlista' => 'pre',
            'e-konyv-sikerlista' => 'e_sold',
        };
    }

    private function mapRankedPageTitle(string $slug): string
    {
        return match ($slug) {
            'eladasi-sikerlista' => 'Eladási sikerlista',
            'akcios-sikerlista' => 'Akciós sikerlista',
            'elojegyzes-sikerlista' => 'Előjegyzési sikerlista',
            'e-konyv-sikerlista' => 'E-könyv sikerlista',
        };
    }

    private function isLoggedIn()
    {
        if ($token = request()->bearerToken()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            if (! empty($accessToken)) {
                $this->guest = false;
                $this->customer = $accessToken->tokenable;
            }
        }
    }

    public function getProductPriceMovement(string $productIsbn)
    {
        $product = Product::where('isbn', $productIsbn)->first();

        if (is_null($product)) {
            return response('', 404);
        }
    }
}
