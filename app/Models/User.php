<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject{
    use HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'tercero_id',
        'fecha_ultimo_ingreso',
        'activo'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier(){
	return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }
    public static function actualizarUltimoAcceso($email){
        $user = User::where('email', $email)->first();
        $user->fecha_ultimo_ingreso = Carbon::now();
        $user->save();
        return $user;
    }
    public static function updateData($data){
        $user = User::find($data['id']);
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->activo = $data['activo'];
        if($data['password'] != '' && $data['password'] != null){
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return $user;
    }
    public static function createUser($data, $password = null){
        $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%&');
        $data['password'] = Hash::make($password == null ? substr($random, 0, 10) : $password);
        $data['fecha_registro'] = Carbon::now();
        $user_created = User::create($data);
        return $user_created;
    }

    
}
