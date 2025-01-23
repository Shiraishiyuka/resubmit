<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\address;

class AddressController extends BaseController
{
    public function edit($product_id) {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください。');
        }

        // 指定された商品の確認
        $product = Product::findOrFail($product_id);


        // 商品に紐づく住所情報を取得または初期化
        $address = Address::firstOrNew(
            ['user_id' => $user->id, 'product_id' => $product_id],
            ['post_code' => '', 'address' => '', 'building' => '']
        );


        // アドレスが空の場合、ユーザープロファイルの値を設定
        if (!$address->exists) {
            $profile = $user->userProfile;
            $address->post_code = $profile->post_code ?? '';
            $address->address = $profile->address ?? '';
            $address->building = $profile->building ?? '';
        }

        return view('address', compact('address', 'product'));
    }


    public function update(Request $request, $id) {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください。');
        }

        $product_id = $request->input('product_id');

        if (!$product_id) {
            return redirect()->back()->withErrors(['error' => '商品IDが見つかりません。']);
    }



    // バリデーション
    $request->validate([
        'post_code' => 'nullable|max:10',
        'address' => 'nullable|max:255',
        'building' => 'nullable|max:255',
    ], [
            
        'post_code.max' => '郵便番号は10文字以内で入力してください。',
        'address.max' => '住所は255文字以内で入力してください。',
        'building.max' => '建物名は255文字以内で入力してください。',
    ]);

    $address = Address::updateOrCreate(
        ['user_id' => $user->id, 'product_id' => $product_id],
        $request->only(['post_code', 'address', 'building'])
    );


    return redirect()->route('purchase', ['id' => $product_id])->with('success', '住所情報が更新されました。');

    }
    
}
