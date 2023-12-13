<?php

namespace Alomgyar\BookRecommendation;

use Alomgyar\Authors\Author;
use Alomgyar\Publishers\Publisher;
use Alomgyar\Subcategories\Subcategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorSelectResource;
use App\Http\Resources\CategorySelectResource;
use App\Http\Resources\PublisherSelectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class BookRecommendationController extends Controller
{
    public function getBookRecommendationPage()
    {
        return view('bookrecommendation::index');
    }

    public function authAndRedirect(BookRecommendationLoginRequest $request)
    {
        $validated = $request->validated();

        // $validUsername = 'alomgyar-kiajanlo';
        // $validPassword = 'feltoltokEnIsAjanlast';
        // CHECK FOR USERNAME & PASSWORD
        if ($validated['email'] == 'alomgyar-kiajanlo') {
            $validated['email'] = 'alomgyar-kiajanlo@alomgyar.hu';
        }
        $user = auth()->attempt(['email' => $validated['email'], 'password' => $validated['password']]);
        if (! $user) {
            return redirect()->back()->withErrors(['Hibás felhasználónév vagy jelszó!']);
        }

        // IF EVERYTHING IS OK, REDIRECT TO UPLOAD PAGE
        //$url = URL::signedRoute('recommendation.upload', ['username' => $validUsername]);

        return redirect()->route('recommendation.upload');
    }

    public function getBookRecommendationUploadPage()
    {
        return view('bookrecommendation::show');
    }

    public function authorSearch(Request $request)
    {
        $term = trim($request->q);

        $authors = Author::query()
            ->select('id', 'title')
            ->search($term)
            ->latest()
            ->paginate(25);

        return response([
            'results' => AuthorSelectResource::collection($authors),
            'pagination' => [
                'more' => $authors->currentPage() !== $authors->lastPage(),
            ],
        ]);
    }

    public function publisherSearch(Request $request)
    {
        $term = trim($request->q);

        $publishers = Publisher::query()
            ->select('id', 'title')
            ->search($term)
            ->latest()
            ->paginate(25);

        return response([
            'results' => PublisherSelectResource::collection($publishers),
            'pagination' => [
                'more' => $publishers->currentPage() !== $publishers->lastPage(),
            ],
        ]);
    }

    public function categorySearch(Request $request)
    {
        $term = trim($request->q);

        $categories = Subcategory::query()
            ->select('id', 'title')
            ->search($term)
            ->latest()
            ->paginate(25);

        return response([
            'results' => CategorySelectResource::collection($categories),
            'pagination' => [
                'more' => $categories->currentPage() !== $categories->lastPage(),
            ],
        ]);
    }
}
