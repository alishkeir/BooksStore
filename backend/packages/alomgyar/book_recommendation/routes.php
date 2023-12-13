<?php

use Alomgyar\BookRecommendation\BookRecommendationController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    //Route::middleware('auth')->group(function () {
    Route::get('kiajanlo', [BookRecommendationController::class, 'getBookRecommendationPage'])->name('recommendation');
    Route::post('kiajanlo', [BookRecommendationController::class, 'authAndRedirect'])->name('recommendation.post');
    Route::middleware('auth.basic')->group(function () {
        Route::get('kiajanlo/feltoltes', [BookRecommendationController::class, 'getBookRecommendationUploadPage'])
        ->name('recommendation.upload');
        Route::get('kiajanlo/authors', [BookRecommendationController::class, 'authorSearch'])->name('recommendation.authorSearch');
        Route::get('kiajanlo/publishers', [BookRecommendationController::class, 'publisherSearch'])->name('recommendation.publisherSearch');
        Route::get('kiajanlo/categories', [BookRecommendationController::class, 'categorySearch'])->name('recommendation.categorySearch');
    });
    //});
});
