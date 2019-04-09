<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Member extends Authenticatable
{
    use Notifiable;
    public $primaryKey = 'Username';
    protected $fillable = [
        'Nama', 'Password','Alamat','Provinsi','Kabupaten','Kecamatan','NoTelepon','Status','Profesi','Foto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'Username', 'Password', 'remember_token',
    ];

    public function articles()
    {
        return $this->hasMany('App\Survey');
    }
}
