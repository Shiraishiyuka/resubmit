<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserProfile;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\UserProfileController;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

      public function login(LoginRequest $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'login_error' => 'ログイン情報が登録されていません',
        ])->withInput($request->only('email'));
    }

    $user = Auth::user();

    // 二段階認証トークンを生成
    $twoFactorToken = Str::random(6);
    Session::put('two_factor_token', $twoFactorToken);
    Session::put('user_id', $user->id);

    // メールでトークンを送信
    Mail::raw("二段階認証コード: {$twoFactorToken}", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('二段階認証コード');
    });

    return redirect()->route('two_factor.form');
}

public function showTwoFactorForm()
{
    return view('emails.two_factor');
}

public function verifyTwoFactor(Request $request)
{
    $request->validate([
        'two_factor_token' => 'required|string',
    ]);

    if (Session::get('two_factor_token') !== $request->two_factor_token) {
        return back()->withErrors([
            'two_factor_error' => '認証コードが正しくありません。',
        ]);
    }

    Auth::loginUsingId(Session::get('user_id'));

    Session::forget(['two_factor_token', 'user_id']);

    // ログインしたユーザーの取得
    $user = Auth::user();

    // 初回ログインかどうかを判別
    if (!$user->userProfile()->exists()) {
        // プロフィールが存在しない場合はプロフィール設定画面にリダイレクト
        return redirect()->route('profile_setting')->with('status', '初回ログインです。プロフィールを設定してください。');
    }

    // 通常のホームページにリダイレクト
    return redirect('/')->with('status', 'ログインしました');
}
}
