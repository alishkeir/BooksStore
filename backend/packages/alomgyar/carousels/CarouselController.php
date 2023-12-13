<?php

namespace Alomgyar\Carousels;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarouselController extends Controller
{
    public function index(): Response
    {
        return response()->view('carousels::index');
    }

    public function create(): Response
    {
        return response()->view('carousels::create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validateRequest();

        Carousel::create($request->all());

        session()->flash('success', 'Carousel sikeresen létrehozva!');

        return redirect()->route('carousels.index');
    }

    public function destroy(Carousel $carousel): RedirectResponse
    {
        $carousel->delete();

        return redirect()->route('carousels.index')->with('success', 'Carousel '.__('messages.deleted'));
    }

    protected function validateRequest(): array
    {
        return request()->validate([
            'title' => 'required',
        ]);
    }
}
