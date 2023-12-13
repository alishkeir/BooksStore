<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBasicGamingFormRequest;
use App\PrizeGamingForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreBasicGamingFormApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreBasicGamingFormRequest $request)
    {
        $prizeGamingForm = PrizeGamingForm::create($request->validated());

        return response()->json('Super!', Response::HTTP_CREATED);
    }
}
