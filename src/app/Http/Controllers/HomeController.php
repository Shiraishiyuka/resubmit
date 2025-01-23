<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function home(Request $request) 
    {
        $redirect = $this->handleRedirects($request);
        if ($redirect) {
            return $redirect;
        }

        // 検索処理を呼び出し
        $products = $this->getProducts($request);

        // 必要な変数をビューに渡す
        $tab = $request->get('tab', 'recommend');
        $searchQuery = $request->input('search', '');

        return view('home', compact('products', 'tab', 'searchQuery'));
    }

    public function mylist(Request $request)
    {
        $request->merge(['tab' => 'mylist']);
        return $this->home($request);
    }
}
