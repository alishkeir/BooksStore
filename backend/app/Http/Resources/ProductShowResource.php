<?php

namespace App\Http\Resources;

use Alomgyar\Authors\ApiAuthor as Author;
use Alomgyar\Customers\Customer;
use Alomgyar\Products\ApiProduct as Product;
use Alomgyar\RankedProducts\Services\RankedProductService;
use App\Http\Traits\ImageTrait;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\Sanctum;

class ProductShowResource extends JsonResource
{
    use ImageTrait;

    public static $wrap = 'data';

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $priceSale = $this->price(request('store'))->price_sale ?? null;
        $priceList = $this->price(request('store'))->price_list ?? null;
        $discountPercent = $this->price(request('store'))->discount_percent ?? null;

        if (request('store') == 2) {
            if ($token = request()->bearerToken()) {
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($token);
                if (! empty($accessToken)) {
                    $this->customer = $accessToken->tokenable;
                    if (($this->publisher_id ?? false) == 38) { //TODO
                        $discount = $this->customer->personal_discount_alomgyar;
                    } else {
                        $discount = $this->customer->personal_discount_all;
                    }
                    $priceSalePersonal = round($priceList - (($priceList / 100) * $discount));
                    if ($priceSalePersonal < $priceSale) {
                        $priceSale = $priceSalePersonal;
                        $discountPercent = round(100 - (($priceSale / $priceList) * 100));
                    }
                }
            }
        }

        if ($this->cover) {
            $this->cover = $this->getOptimizedImage($this->cover);
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'authors' => $this->author->isNotEmpty() ? AuthorResource::collection($this->author) : [],
            'rating' => [
                'current_rating' => number_format(Review::where('product_id', $this->id)->whereStore(request('store'))->avg('review'), 0),
                'rating_count' => Review::where('product_id', $this->id)->whereStore(request('store'))->count(),
                'user_rating' => null,
            ],
            'cover' => $this->cover,
            'price_list' => $priceList, // hotfix, TODO
            'price_sale' => $priceSale, // hotfix, TODO
            'discount_percent' => $discountPercent, // hotfix, TODO
            'isbn' => $this->isbn,
            'publisher' => $this->publisher?->title,
            'release_year' => $this->release_year,
            'categories' => Product::getCategoriesBySubcategories($this->subcategories),
            'expected_delivery_time' => '2-5 munkanap', // TODO: szállítási idő
            'selected' => false, // Mivel itt nincs beazonosítva a customer, mindig false
            'type' => $this->type,
            'state' => $this->state === 1 ? 'preorder' : 'normal', // preorder
            'published_at' => $this->formatted_published_at,
            'ranked_list' => $this->ranked ? $this->getRankedArray() : [],
            'authors_books' => $this->author->isNotEmpty() ? Author::authorBooks($this->primaryAuthor->isNotEmpty() ? $this->primaryAuthor->first() : $this->author->first(), $this->id) : [],
            'similar_books' => $this->subcategories->isNotEmpty() ? Product::similarBooks($this->subcategories, $this->type, $this->id) : [],
            'shop_info' => [
                'discount_rate' => option(sprintf('default_discount_rate_%s', $this->getStoreSlug())),
                'free_shopping_limit' => option(sprintf('free_shipping_limit_%s', $this->getStoreSlug())),
            ],
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'number_of_pages' => $this->number_of_pages,
            'language' => $this->language,
            'book_binding_method' => $this->book_binding_method,
        ];
    }

    private function getRankedArray(): array
    {
        $service = RankedProductService::create();

        return [
            [
                'title' => $service->getLabel($this->ranked->type),
                'slug' => $service->getListUrl($this->ranked->type),
                'place' => $this->ranked->rank,
            ],
        ];
    }

    private function getStoreSlug(): string
    {
        $storeId = (int) request('store');

        return match ($storeId) {
            0 => 'alomgyar',
            1 => 'olcsokonyvek',
            2 => 'nagyker',
        };
    }
}
