<?php

namespace Alomgyar\Methods;

use App\Http\Controllers\Controller;

class MethodsController extends Controller
{
    public function index()
    {
        $model = PaymentMethod::latest()->paginate(25);

        return view('methods::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('methods::create');
    }

    public function store()
    {
        $this->validateRequest();
        $methods = PaymentMethod::create(request()->all());

        session()->flash('success', 'Rendelési lehetőség sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $methods, 'return_url' => route('methods.index')]);
    }

    public function edit(PaymentMethod $method)
    {
        return view('methods::edit', [
            'model' => $method,
            'route' => route('methods.update', ['method' => $method]),
        ]);
    }

    public function update(PaymentMethod $method)
    {
        $data = request()->all();
        $this->validateRequest($data);

        $checks = ['status', 'status_0', 'status_1', 'status_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $method->update($data);
        session()->flash('success', 'Rendelési lehetőség sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $method, 'return_url' => route('methods.index')]);
    }

    protected function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
        ]);
    }
}
