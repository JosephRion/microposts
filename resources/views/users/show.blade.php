@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            {{--内容を大幅に書き換え L15 C10.4 ここから
            <div class="card">
               
               <div class="card-header">
                    <h3 class="card-title">{{ $user->name }}</h3>
                </div>
                <div class="card-body">
                    -- ユーザのメールアドレスをもとにGravatarを取得して表示 --
                    <img class="rounded img-fluid" src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt="">
                </div>
            </div>
            
            -- フォロー／アンフォローボタン 表示 2022.07.11 L15 C10.3 --
            @include('user_follow.follow_button')
            内容を大幅に書き換え L15 C10.4 ここまで--}}
            {{-- ユーザ情報 L15 C10.4--}}
            @include('users.card')
            
        </aside>
        
        
        <div class="col-sm-8">
            {{--内容を大幅に書き換え後半 L15 C10.4 ここから
            <ul class="nav nav-tabs nav-justified mb-3">
               -- ユーザ詳細タブ --
                <li class="nav-item">
                    <a href="{{ route('users.show', ['user' => $user->id]) }}" class="nav-link {{ Request::routeIs('users.show') ? 'active' : '' }}">
                        TimeLine
                        <span class="badge badge-secondary">{{ $user->microposts_count }}</span>
                    </a>
                </li>
                -- フォロー一覧タブ --
                <li class="nav-item"><a href="#" class="nav-link">Followings</a></li>
                -- フォロワー一覧タブ --
                <li class="nav-item"><a href="#" class="nav-link">Followers</a></li>
            </ul>
            内容を大幅に書き換え後半 L15 C10.4 ここまで--}}
             {{-- タブ --}}
            @include('users.navtabs')
            
            @if (Auth::id() == $user->id)
                {{-- 投稿フォーム --}}
                @include('microposts.form')
            @endif
            {{-- 投稿一覧 --}}
            @include('microposts.microposts')
            
        </div>
    </div>
@endsection