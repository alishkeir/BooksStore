<?php

namespace Alomgyar\Categories;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $model = Category::latest()->paginate(25);

        return view('categories::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('categories::create');
    }

    public function store()
    {
        $data = $this->validateRequest();
        $category = Category::create(request()->all());

        session()->flash('success', 'Kategória sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $category, 'return_url' => route('categories.index')]);
//        return redirect()->route('categories.index')->with('success', 'Category sikeresen létrehozva!');
    }

    public function edit(Category $category)
    {
        if (! auth()->user()->hasRole(['skvadmin', 'super-admin'])) {
            abort(403, __('messages.not-authorized'));
        }

        return view('categories::edit', [
            'model' => $category,
        ]);
    }

    public function update(Category $category)
    {
        $data = request()->all();
        $this->validateRequest();
        if (! ($data['status'] ?? false)) {
            $data['status'] = 0;
        }
        $category->update($data);

        //handle subcategories
        if (! empty($data['subcategory'] ?? false)) {
            $category->subcategories()->sync($data['subcategory']);
        }
        session()->flash('success', 'Kategória sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $category, 'return_url' => route('categories.edit', ['category' => $category->id])]);
    }

    public function show(Category $category)
    {
        if (! auth()->user()->hasRole(['skvadmin', 'super-admin'])) {
            abort(403, __('messages.not-authorized'));
        }

        return view('categories::show', [
            'model' => $category,
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'slug' => 'required',
        ]);
    }
}
