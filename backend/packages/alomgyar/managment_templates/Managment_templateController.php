<?php

namespace Alomgyar\Managment_templates;

use App\Http\Controllers\Controller;

class Managment_templateController extends Controller
{
    public function index()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::index', [
            'model' => $model,
        ]);
    }

    public function orders()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::orders', [
            'model' => $model,
        ]);
    }

    public function order()
    {
        return view('managment_templates::order');
    }

    public function stock()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::stock', [
            'model' => $model,
        ]);
    }

    public function stockhistory()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::stock-history', ['model' => $model]);
    }

    public function stockwarehouse()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::stock-warehouse', ['model' => $model]);
    }

    public function stocksupplier()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::stock-supplier', ['model' => $model]);
    }

    public function stockin()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::stock-in', ['model' => $model]);
    }

    public function stockout()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::stock-in', ['model' => $model]);
    }

    public function salesriport()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::salesriport', ['model' => $model]);
    }

    public function warehouse()
    {
        $model = Managment_template::latest()->paginate(25);

        return view('managment_templates::warehouse', ['model' => $model]);
    }
}
