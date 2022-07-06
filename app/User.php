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
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
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
    
}
