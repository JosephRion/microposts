<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     * User Model L15 C9.1
     * これが無いとエラーになる。
     * BadMethodCallException with message 'Call to undefined method App\User::microposts()'
     * ログイン中のユーザーが登録したタスクを取得したい場合は
     * 1. ログイン中のユーザー情報を取得
     * 2. tasksテーブルの「user_idカラム」と1のユーザーのidが一致しているレコードを取得
     * という操作で行うことができます。
     * Laravelはこれらの操作をシンプルな記述で行うことができ、そのための指定が「リレーション」です。
     * Lesson 15Chapter 9.1 Model
     */
    public function microposts() // リレーション
    {
        return $this->hasMany(Micropost::class);
    }
    /**
     *このようにリレーションを定義しておくことで、 
     *Userクラスのインスタンス->microposts()->paginate() という指定だけで
     *「モデル名やリレーションの記述をもとに、
     *上記の1~2の手順を裏側で自動的に行い、必要なデータを取得」してくれます。
    **/
    
    /**
     * このユーザに関係するモデルの件数をロードする。
     * Micropostの数をカウントする機能を追加
     * Userが持つMicropostの数をカウントするためのメソッドも作成しておきます。
     * loadCount メソッドの引数に指定しているのはリレーション名です。
     * 先ほどモデル同士の関係を表すメソッドを定義しましたが、そのメソッド名がリレーション名になります。
     * これによりUserのインスタンスに {リレーション名}_count プロパティが追加され、件数を取得できるようになります。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount('microposts');
    }
    
    /**
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     * Lesson 15Chapter 10.1 Model  多対多の関係
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     * Lesson 15Chapter 10.1 Model  多対多の関係
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        // すでにフォローしているか
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // フォロー済み、または、自分自身の場合は何もしない
            return false;
        } else {
            // 上記以外はフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfollow($userId)
    {
        // すでにフォローしているか
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // フォロー済み、かつ、自分自身でない場合はフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 上記以外の場合は何もしない
            return false;
        }
    }

    /**
     * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    
    /**
     * このユーザに関係するモデルの件数をロードする。
     * フォロー／フォロワー数のカウント L15 C10.3
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers']);
    }
    
}
