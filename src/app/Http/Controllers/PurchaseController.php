<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\user;
use App\Models\address;
use App\Models\Product;
use App\Models\UserProfile;

class PurchaseController extends BaseController
{
    public function purchase(Request $request, $id)
    {
        $user = auth()->user();
        $product = Product::find($id);

        if (!$product) {
            abort(404, '商品が見つかりません');
        }

        $redirect = $this->handleRedirects($request);
        if ($redirect) {
            return $redirect;
        }

        $address = Address::where('user_id', $user->id)->where('product_id', $product->id)->first();
        if ($request->has('address')) {
            return redirect()->route('address.edit', ['product_id' => $product->id]);
        }

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'payment_method' => 'required|in:コンビニ払い,カード払い',
            ]);


        $address = Address::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $product->id],
            [
                'post_code' => $request->post_code ?? $product->user->userProfile->post_code ?? '未設定',
                'address' => $request->address ?? $product->user->userProfile->address ?? '未設定',
                'building' => $request->building ?? $product->user->userProfile->building ?? '未設定',
                'payment_method' => $validated['payment_method']
            ]
        );


            return redirect()->route('mypage')->with('success', '購入が完了しました');
        }

        $address = Address::where('user_id', $user->id)->first();
        if (!$address) {
            $address = new \stdClass();
            $profile = UserProfile::where('user_id', $user->id)->first();
            $address->post_code = $profile->post_code ?? '未設定';
            $address->address = $profile->address ?? '未設定';
            $address->building = $profile->building ?? '未設定';
        }

        return view('purchase', compact('product', 'address'));
    }
}
