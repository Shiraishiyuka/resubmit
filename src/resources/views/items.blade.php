@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}" />
<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
@endsection

@section('header')
@include('partials.header')
@endsection

@section('content')
<div class="product-page">
    <div class="items">
        <div class="item_box">
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="product-image">
        </div>
    </div>

    <div class="items-text">
        <div class="form-top">
            <label class="product-name">{{ $product->name }}</label>
            <p>プランド名</p>
            <div class="price-container">
                <label class="product-price">{{ $product->price }}</label>
            </div>

            <div class="icon-space">
                <div class="icon-item">
                    <form action="{{ route('items.like', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; cursor: pointer;">
                            <img src="{{ asset(
                                optional($product->interactions)
                                    ->where('type', 'like')
                                    ->where('user_id', Auth::id())
                                    ->isNotEmpty()
                                ? 'storage/images/yellow-star.png' 
                                : 'storage/images/star.png'
                            ) }}" 
                            class="icon-container">
                        </button>
                    </form>
                    <span>{{ optional($product->interactions)->where('type', 'like')->count() ?? 0 }}</span>
                </div>

                <div class="icon-item">
                    <img src="{{ asset('storage/images/download-1.png') }}" class="like-icon">
                    <span>{{ $product->interactions->where('type', 'comment')->count() }}</span>
                </div>
            </div>
        </div>

        <form action="{{ $product->address ? '#' : route('purchase', ['id' => $product->id]) }}" method="get">
            @csrf
            <div class="purchase-button">
                @if ($product->address)
                    <button class="purchase" disabled>売り切れ</button>
                @else
                    <button class="purchase">購入手続きへ</button>
                @endif
            </div>
        </form>

        <label class="description">商品説明</label>
        <label class="product-description">{{ $product->description }}</label>

        <label class="condition">商品の情報</label>
        
        <div class="categories">
            <span class="category">カテゴリー</span>
    @if (!empty($product->category) && is_array($product->category))
        @foreach ($product->category as $category)
            <span class="category-label">{{ $category }}</span>
        @endforeach
    @else
        <span class="category-label">{{ $product->category }}</span>
    @endif
</div>

        <label class="product-condition">商品の状態<span class="condition-name">{{ $product->condition }}</span></label>

        <div class="comment-space">
            <span class="text">コメント（{{ $product->interactions->where('type', 'comment')->count() }}）</span>
            <div class="comment-list">
    @foreach ($product->interactions->where('type', 'comment') as $interaction)
        <div class="user-comment">
            <div class="user">
                <img src="{{ $interaction->user->userprofile->profile_image_path }}" 
                    alt="ユーザーアイコン" 
                    class="icon-image">
                    <span class="user-name">{{ $interaction->user->userprofile->name }}</span>
            </div>
            <p class="comment-text">{{ $interaction->comment }}</p>
        </div>
    @endforeach
</div>

            <form action="{{ route('product.comment', $product->id) }}" method="POST">
                @csrf
                <div class="product-text">商品へのコメント</div>
                <textarea name="comment" placeholder="コメントを入力してください" class="comment-textarea"></textarea>
                <div class="comment-bottom">
                    <button class="comment">コメントを送信する</button>
                </div>
                @error('comment')
                    <div class="form_error">{{ $message }}</div>
                @enderror
            </form>
        </div>
    </div>
</div>



@endsection