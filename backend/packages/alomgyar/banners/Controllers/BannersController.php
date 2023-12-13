<?php

namespace Alomgyar\Banners\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BannersController extends Controller
{
    public function __invoke(): Response
    {
        return response()->view('banners::_form');
    }
}
