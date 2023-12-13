<?php

namespace Alomgyar\Synchronizations;

use App\Http\Controllers\Controller;

class SynchronizationController extends Controller
{
    public function index()
    {
        $model = Synchronization::latest()->paginate(25);

        return view('synchronizations::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('synchronizations::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $synchronization = Synchronization::create(request()->all());

        session()->flash('success', 'Synchronization sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $synchronization, 'return_url' => route('synchronizations.index')]);
//        return redirect()->route('synchronizations.index')->with('success', 'Synchronization sikeresen létrehozva!');
    }

    public function edit(Synchronization $synchronization)
    {
        return view('synchronizations::edit', [
            'model' => $synchronization,
        ]);
    }

    public function update(Synchronization $synchronization)
    {
        $data = $this->validateRequest();
        $synchronization->update($data);

        session()->flash('success', 'Synchronization sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $synchronization, 'return_url' => route('synchronizations.index')]);
//        return redirect()->route('synchronizations.index', ['synchronization' => $synchronization->id]);
    }

    public function show(Synchronization $synchronization)
    {
        return view('synchronizations::show', [
            'model' => $synchronization,
        ]);
    }

    public function destroy(Synchronization $synchronization)
    {
        $synchronization->delete();

        return redirect()->route('synchronizations.index')->with('success', 'Synchronization '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    }
}
