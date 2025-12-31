<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CourseRequest;
use App\Models\Shop;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * コース一覧
     */
    public function index(Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        $courses = $shop->courses()->latest()->get();

        return view('shop.courses.index', compact('shop', 'courses'));
    }

    /**
     * コース作成フォーム
     */
    public function create(Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        return view('shop.courses.create', compact('shop'));
    }

    /**
     * コース作成
     */
    public function store(CourseRequest $request, Shop $shop)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id()) {
            abort(403, 'この店舗にアクセスする権限がありません。');
        }

        $validated = $request->validated();

        $validated['shop_id'] = $shop->id;
        $validated['is_active'] = $request->has('is_active');

        Course::create($validated);

        return redirect()
            ->route('shop.courses.index', $shop)
            ->with('success', 'コースを作成しました。');
    }

    /**
     * コース編集フォーム
     */
    public function edit(Shop $shop, Course $course)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id() || $course->shop_id !== $shop->id) {
            abort(403, 'このコースを編集する権限がありません。');
        }

        return view('shop.courses.edit', compact('shop', 'course'));
    }

    /**
     * コース更新
     */
    public function update(CourseRequest $request, Shop $shop, Course $course)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id() || $course->shop_id !== $shop->id) {
            abort(403, 'このコースを更新する権限がありません。');
        }

        $validated = $request->validated();

        $validated['is_active'] = $request->has('is_active');

        $course->update($validated);

        return redirect()
            ->route('shop.courses.index', $shop)
            ->with('success', 'コースを更新しました。');
    }

    /**
     * コース削除
     */
    public function destroy(Shop $shop, Course $course)
    {
        // 自分の店舗かチェック
        if ($shop->user_id !== auth()->id() || $course->shop_id !== $shop->id) {
            abort(403, 'このコースを削除する権限がありません。');
        }

        $course->delete();

        return redirect()
            ->route('shop.courses.index', $shop)
            ->with('success', 'コースを削除しました。');
    }
}
