<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Address;
use App\Models\UserProfile;
use App\Models\Product;

class MypageController extends BaseController
{
    public function mypage(Request $request) {

        $searchQuery = $request->input('search', ''); 
        $tab = $request->get('tab', 'sell');
        $products = collect();
        $userprofile = Auth::user()->userProfile;

        if ($tab === 'buy') {
            $purchasedProductIds = Address::where('user_id', Auth::id())->pluck('product_id');
            $query = Product::whereIn('id', $purchasedProductIds);
        } elseif ($tab === 'sell') {
            $query = Product::where('user_id', Auth::id());
        } else {
            $query = null;
        }



        if (isset($query) && !empty($searchQuery)) {
            $query->where('name', 'LIKE', "%{$searchQuery}%");
        }

        if (isset($query)) {
            $products = $query->get();
        }

        $redirect = $this->handleRedirects($request);
        if ($redirect) {
            return $redirect;
        }




        return view('mypage', compact('products', 'tab', 'searchQuery', 'userprofile'));

    }

    // 出品商品ページ
    public function sell(Request $request)
    {
        $products = Product::where('user_id', Auth::id())->get();
        $userprofile = Auth::user()->userProfile;
        $searchQuery = $request->input('search', '');
        return view('mypage', compact('products', 'userprofile', 'searchQuery'))->with('tab', 'sell');
    }

    // 購入商品ページ
    public function buy(Request $request)
    {
        $purchasedProductIds = Address::where('user_id', Auth::id())->pluck('product_id');
        $products = Product::whereIn('id', $purchasedProductIds)->get();
        $userprofile = Auth::user()->userProfile;
        $searchQuery = $request->input('search', '');
        return view('mypage', compact('products', 'userprofile', 'searchQuery'))->with('tab', 'buy');
    }
}
