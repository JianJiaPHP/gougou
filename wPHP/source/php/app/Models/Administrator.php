<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\Administrator
 *
 * @property int $id
 * @property string|null $account 登陆账号
 * @property string|null $nickname 昵称
 * @property mixed $avatar 头像
 * @property string|null $password 密码
 * @property int $role_id 角色id 0=超级管理员
 * @property string|null $token 用户token
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Administrator whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Administrator extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table = "administrators";
    protected $guarded = [];


    /**
     * 头像拼接连接
     * @param $value
     * @return mixed
     * @author Aii
     */
    public function getAvatarAttribute($value)
    {
        $url = $value;
        $preg = "/^http(s)?:\\/\\/.+/";
        if(!preg_match($preg,$value)) {
            $url =  env('APP_URL').$value;
        }
        return  $url;
    }
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


}
