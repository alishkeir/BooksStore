<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrizeGamingFormRequest;
use App\PrizeGamingForm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StorePrizeGamingFormApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StorePrizeGamingFormRequest $request)
    {
        if (Carbon::now() >= Carbon::createFromFormat('Y/m/d', PrizeGamingForm::LAST_AVAILABLE_BEFORE)) {
            return response()->json('Error', Response::HTTP_FORBIDDEN);
        }
        $prizeGamingForm = PrizeGamingForm::create($request->validated());
        $prizeGamingForm->prize_game_form = PrizeGamingForm::CHRISTMAS_GAME_2022;
        $prizeGamingForm->save();

        return response()->json('Super!', Response::HTTP_CREATED);
    }
}
