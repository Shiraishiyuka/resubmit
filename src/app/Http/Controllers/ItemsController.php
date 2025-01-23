<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Userprofile;
use App\Models\Product;
use App\Models\Interaction;


class ItemsController extends BaseController
{
    public function item(Request $request, $id)
{
    // 商品情報とコメント情報をロード
    $product = Product::with([
        'interactions' => function ($query) {
            $query->where('type', 'comment')->with('user.userprofile');
        }
    ])->findOrFail($id);

    $userprofile = Auth::user()->userProfile ?? null; // ログインユーザーのプロフィール

    $tab = $request->get('tab', 'recommend');
    $searchQuery = $request->input('search', '');

    return view('items', compact('product', 'tab', 'searchQuery', 'userprofile'));
}

public function like($id)
{
    // 認証済みのユーザーかどうかチェック
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'ログインしてください。');
    }

    // ユーザーの「いいね」が既に存在するか確認
    $existingLike = Interaction::where('user_id', auth()->id())
        ->where('product_id', $id)
        ->where('type', 'like')
        ->first();

    if ($existingLike) {
        // 既存の「いいね」を削除（解除）
        $existingLike->delete();
    } else {
        // 新しい「いいね」を作成
        Interaction::create([
            'user_id' => auth()->id(),
            'product_id' => $id,
            'type' => 'like',
        ]);
    }

    // ページをリロードして、いいねの状態を更新
    return redirect()->back();
}



    

    public function comment(CommentRequest $request, $id)
    {
        // 非ログインユーザーへの対応
        if (!auth()->check()) {
            return redirect()->back()->withErrors(['comment' => 'コメントを送信できるのはユーザーのみです。']);
        }


        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        Interaction::create([
            'user_id' => auth()->id(),
            'product_id' => $id,
            'type' => 'comment',
            'comment' => $request->comment,
        ]);

        $product = Product::with(['interactions.user.userprofile'])->findOrFail($id);

        $userprofile = Auth::user()->userProfile;


        return redirect()->route('items', ['id' => $id])->with('status', 'コメントが送信されました。');
    }

}
