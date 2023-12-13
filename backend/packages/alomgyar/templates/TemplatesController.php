<?php

namespace Alomgyar\Templates;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function index()
    {
        $model = Templates::latest()->paginate(25);

        return view('templates::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('templates::create');
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest();

        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $templates = Templates::create($data);

        session()->flash('success', 'Sablon sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $templates, 'return_url' => route('templates.index')]);
//        return redirect()->route('templates.index')->with('success', 'Templates sikeresen létrehozva!');
    }

    public function edit(Templates $template)
    {
        return view('templates::edit', [
            'model' => $template,
        ]);
    }

    public function update(Templates $template, Request $request): JsonResponse
    {
        $this->validateRequest();
        $data = $request->all();

        $checks = ['status', 'store_0', 'store_1', 'store_2'];

        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }

        $template->update($data);

        session()->flash('success', 'Sablon sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $template, 'return_url' => route('templates.index')]);
    }

    public function show(Templates $templates)
    {
        return view('templates::show', [
            'model' => $templates,
        ]);
    }

    public function destroy(Templates $templates)
    {
        $templates->delete();

        return redirect()->route('templates.index')->with('success', 'Templates '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
    }
}
