<?php

namespace App\Http\Controllers;

use Alomgyar\Categories\Category;
use Alomgyar\Countries\Country;
use Alomgyar\PickUpPoints\Model\PickUpPoint;
use App\Http\Resources\CountryResource;
use App\Http\Resources\OptionsResource;
use App\Http\Resources\PickUpPointResource;
use App\Http\Resources\SubcategoryResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HelpersApiController extends Controller
{
    use ErrorMessages;

    protected array $validRefs = ['countries', 'options', 'pick_up_points', 'categories', 'pick_up_points'];

    public function __invoke(Request $request)
    {
        if (! in_array(request('ref'), $this->validRefs)) {
            return $this->badRefMessage();
        }

        if (request('ref') === 'countries') {
            return $this->getCountries();
        }
        if (request('ref') === 'options') {
            return $this->getOptions();
        }

        if (request('ref') === 'pick_up_points') {
            return $this->getPickUpPoints();
        }

        if (request('ref') === 'categories') {
            return $this->getCategories();
        }
    }

    private function getCountries()
    {
        return response([
            'data' => [
                'countries' => CountryResource::collection(Country::active()->get()),
            ],
        ]);
    }

    private function getOptions()
    {
        return response([
            'data' => [
                'options' => new OptionsResource(options()),
            ],
        ]);
    }

    private function getCategories()
    {
        $categories = Cache::remember('categories', 300, function () {
            return Category::active()->orderBy('title', 'ASC')->get();
        });
        // $categories = Category::active()->orderBy('title', 'ASC')->get();

        return [
            'categories' => SubcategoryResource::collection($categories),
        ];
    }

    private function getPickUpPoints()
    {
        $points = PickUpPoint::query()
            ->select([
                'provider',
                'provider_name',
                'provider_id',
                'provider_type',
                'name',
                'lat',
                'long as lng',
                'zip',
                'city',
                'address',
            ])
            ->where('status', PickUpPoint::STATUS_ACTIVE)
            ->where('provider', '!=', PickUpPoint::PROVIDER_EASYBOX)
            ->get();

        return response([
            'data' => [
                'options' => PickUpPointResource::collection($points),
            ],
        ]);
    }
}
