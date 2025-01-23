@extends('layouts.app')

@section('content')
<div class="container">
    <h2>二段階認証</h2>
    <form method="POST" action="{{ route('two_factor.verify') }}">
        @csrf
        <div class="form-group">
            <label for="two_factor_token">認証コード</label>
            <input type="text" name="two_factor_token" id="two_factor_token" class="form-control" required>
        </div>
        @error('two_factor_error')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary">送信</button>
    </form>
</div>
@endsection