<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\SellRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellController extends BaseController
{
    public function sell()
    {
        return view('sell');
    }

    public function store(SellRequest $request)
{
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $relativePath = 'images/' . basename($path);
    } else {
        return redirect()->back()->withErrors(['image' => '画像ファイルがアップロードされていません']);
    }

    $validated = $request->validated();

    // 商品データを保存
    $product = Product::create([
        'user_id' => Auth::id(),
        'name' => $validated['name'],
        'description' => $validated['description'],
        'category' => $validated['categories'],
        'condition' => $validated['condition'],
        'price' => $validated['price'],
        'image_path' => $relativePath,
    ]);


    // ホーム画面にリダイレクト
    return redirect()->route('home_route')->with('success', '商品が出品されました！');
}
}
