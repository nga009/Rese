<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopOwnerRequest;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * 管理者ダッシュボード
     */
    public function dashboard()
    {
        $shopOwners = User::where('role', 'shop')
            ->with('shops')
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact('shopOwners'));
    }

    /**
     * 店舗代表者作成フォーム
     */
    public function createShopOwner()
    {
        return view('admin.shop-owners.create');
    }

    /**
     * 店舗代表者を作成
     */
    public function storeShopOwner(StoreShopOwnerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'shop',
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', '店舗代表者を作成しました。');
    }

    /**
     * 店舗代表者一覧
     */
    public function shopOwners()
    {
        $shopOwners = User::where('role', 'shop')
            ->with('shops')
            ->get();

        return view('admin.shop-owners.index', compact('shopOwners'));
    }

    /**
     * 店舗代表者削除
     */
    public function destroyShopOwner(User $user)
    {
        if ($user->role !== 'shop') {
            return back()->with('error', '店舗代表者のみ削除できます。');
        }

        $user->delete();

        return back()->with('success', '店舗代表者を削除しました。');
    }
}
