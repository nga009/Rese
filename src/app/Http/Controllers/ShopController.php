<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::with(['area', 'genre']);

        // エリアでフィルタリング
        if ($request->filled('area_id')) {
            $query->areaFilter($request->area_id);
        }

        // ジャンルでフィルタリング
        if ($request->filled('genre_id')) {
            $query->genreFilter($request->genre_id);
        }

        // キーワード検索
        if ($request->filled('keyword')) {
            $query->search($request->keyword);
        }

        $shops = $query->get();
        $areas = Area::all();
        $genres = Genre::all();

        return view('shops.index', compact('shops', 'areas', 'genres'));
    }

    public function show(Shop $shop)
    {
        // 店舗情報と有効なコースを読み込み
        $shop->load([
            'area',
            'genre',
            'activeCourses' => function ($query) {
                $query->orderBy('price', 'asc');
            }
        ]);

        return view('shops.show', compact('shop'));
    }

}
