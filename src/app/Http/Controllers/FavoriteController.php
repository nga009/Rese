<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    public function toggle(Shop $shop)
    {
        auth()->user()->toggleFavorite($shop);

        return back();
    }
}
