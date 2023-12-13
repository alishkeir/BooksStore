<?php

namespace Alomgyar\Countries;

use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index()
    {
        $model = Country::latest()->paginate(25);

        return view('countries::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('countries::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $country = Country::create(request()->all());

        session()->flash('success', 'Ország sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $country, 'return_url' => route('countries.index')]);
//        return redirect()->route('countries.index')->with('success', 'Country sikeresen létrehozva!');
    }

    public function edit(Country $country)
    {
        return view('countries::edit', [
            'model' => $country,
        ]);
    }

    public function update(Country $country)
    {
        $data = $this->validateRequest();
        $country->update($data);

        session()->flash('success', 'Ország sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $country, 'return_url' => route('countries.index')]);
//        return redirect()->route('countries.index', ['country' => $country->id]);
    }

    public function show(Country $country)
    {
        return view('countries::show', [
            'model' => $country,
        ]);
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('countries.index')->with('success', 'Country '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'code' => 'required',
            'fee' => 'required',
        ]);
    }
}
