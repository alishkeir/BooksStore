<?php

namespace Alomgyar\Recommenders;

use Alomgyar\Recommenders\Repository\RecommenderRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecommendersController extends Controller
{
    public function index()
    {
        $model = Recommender::latest()->paginate(25);

        return view('recommenders::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        $recommender = new Recommender();

        return view('recommenders::create', compact('recommender'));
    }

    public function store(Request $request)
    {
        $this->validateRequest();
        $recommenders = Recommender::create($request->all());

        session()->flash('success', 'Ajánlás sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $recommenders, 'return_url' => route('recommenders.index')]);
    }

    public function edit(Recommender $recommender)
    {
        return view('recommenders::edit', compact('recommender'));
    }

    public function update(Recommender $recommender, Request $request)
    {
        $this->validateRequest();

        $recommender->update($request->only([
            'original_product_id',
            'promoted_product_id',
            'release_date',
            'message_body',
        ]));

        session()->flash('success', 'Recommenders sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $recommender, 'return_url' => route('recommenders.index')]);
    }

    public function show(Recommender $recommender)
    {
        $customerNum = (new RecommenderRepository())->getCustomerNumByProductId($recommender->original_product_id);

        return view('recommenders::show', compact('recommender', 'customerNum'));
    }

    public function destroy(Recommender $recommenders)
    {
        $recommenders->delete();

        return redirect()->route('recommenders.index')->with('success', 'Recommenders '.__('messages.deleted'));
    }
}
