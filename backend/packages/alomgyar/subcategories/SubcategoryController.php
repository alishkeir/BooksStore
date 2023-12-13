<?php

namespace Alomgyar\Subcategories;

use Alomgyar\Categories\Category;
use App\Http\Controllers\Controller;

class SubcategoryController extends Controller
{
    public function index()
    {
        return view('subcategories::index');
    }

    public function create()
    {
        $categories = Category::active()->get();

        return view('subcategories::create', compact('categories'));
    }

    public function store()
    {
        $data = $this->validateRequest();

        $subcategory = Subcategory::create($data);
        $subcategory->categories()->sync(request()->category);

        session()->flash('success', 'Alkategória sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $subcategory, 'return_url' => route('subcategories.index')]);
//        return redirect()->route('subcategories.index')->with('success', 'Subcategory sikeresen létrehozva!');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::get();

        return view('subcategories::edit', [
            'model' => $subcategory,
            'categories' => $categories,
        ]);
    }

    public function update(Subcategory $subcategory)
    {
        $data = request()->all();
        $this->validateRequest();
        if (! ($data['status'] ?? false)) {
            $data['status'] = 0;
        }
        $subcategory->update($data);

        //handle subcategories
        if (! empty($data['category'] ?? false)) {
            $subcategory->categories()->sync($data['category']);
        }
        session()->flash('success', 'Alkategória sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $subcategory, 'return_url' => route('subcategories.edit', ['subcategory' => $subcategory->id])]);
    }

    public function show(Subcategory $subcategory)
    {
        return view('subcategories::show', [
            'model' => $subcategory,
        ]);
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Alkategória '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'slug' => 'required',
        ]);
    }
}
