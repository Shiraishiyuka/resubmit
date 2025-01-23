<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Product;

class BaseController extends Controller
{
    protected function getProducts(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $tab = $request->get('tab', 'recommend');
        $products = collect();

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query = Product::whereHas('interactions', function ($query) {
                    $query->where('user_id', Auth::id())
                          ->where('type', 'like');
                });
            }
        } else {
            // 全商品を取得
            $query = Product::query();
        }

        // 検索条件を適用
        if (isset($query) && !empty($searchQuery)) {
            $query->where('name', 'LIKE', "%{$searchQuery}%");
        }

        // 結果を取得
        return isset($query) ? $query->get() : $products;
    }

    protected function handleRedirects(Request $request)
    {
        if ($request->has('logout')) {
            Auth::logout();
            return redirect()->route('home_route');
        }

        if ($request->has('login') || (!$request->has('logout') && !Auth::check() && ($request->has('mypage') || $request->has('sell')))) {
            return redirect()->route('login.form')->with('message', 'ログインが必要です。');
        }

        if ($request->has('mypage')) {
            return redirect('mypage');
        }

        if ($request->has('sell')) {
            return redirect()->route('sell');
        }

        return null; // リダイレクト不要の場合
    }
}
