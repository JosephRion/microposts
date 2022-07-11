<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $user->name }}</h3>
    </div>
    <div class="card-body">
        {{-- ユーザのメールアドレスをもとにGravatarを取得して表示  2022.07.11 L15 C10.4--}}
        <img class="rounded img-fluid" src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt="">
    </div>
</div>
{{-- フォロー／アンフォローボタン --}}
@include('user_follow.follow_button')