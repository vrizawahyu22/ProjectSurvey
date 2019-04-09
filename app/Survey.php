<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
class Survey extends Eloquent
{
    protected $table    = 'surveys';
    public $primaryKey  = 'IdSurvey';
    public $timestamps  = false;
    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}
