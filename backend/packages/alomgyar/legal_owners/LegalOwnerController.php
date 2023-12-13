<?php

namespace Alomgyar\Legal_owners;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSelectResource;
use Illuminate\Http\Request;

class LegalOwnerController extends Controller
{
    public function index()
    {
        $model = LegalOwner::latest()->paginate(25);

        return view('legal_owners::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('legal_owners::create');
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status'];

        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }

        $legal_owners = LegalOwner::create($data);

        session()->flash('success', 'Jogtulaj sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $legal_owners, 'return_url' => route('legal_owners.index')]);
        //        return redirect()->route('legal_owners.index')->with('success', 'Jogtulaj sikeresen létrehozva!');
    }

    public function edit(LegalOwner $legal_owner)
    {
        return view('legal_owners::edit', [
            'model' => $legal_owner,
        ]);
    }

    public function update(LegalOwner $legal_owner)
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status'];

        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $legal_owner->update($data);

        session()->flash('success', 'Jogtulaj sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $legal_owner, 'return_url' => route('legal_owners.index')]);
        //        return redirect()->route('legal_owners.index', ['writer' => $legal_owners->id]);
    }

    public function show(LegalOwner $legal_owner)
    {
        return view('legal_owners::show', [
            'model' => $legal_owner,
        ]);
    }

    public function destroy(LegalOwner $legal_owner)
    {
        $legal_owner->delete();

        return redirect()->route('legal_owners.index')->with('success', 'Jogtulaj '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
        ]);
    }

    public function search(Request $request)
    {
        $legal_ownerss = LegalOwner::select('id', 'title')
            ->search(trim($request->q))
            ->latest()->paginate(25);

        return response([
            'results' => ProductSelectResource::collection($legal_ownerss),
            'pagination' => [
                'more' => $legal_ownerss->currentPage() !== $legal_ownerss->lastPage(),
            ],
        ]);
    }
}
