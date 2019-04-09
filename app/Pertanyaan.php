<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
class Pertanyaan extends Eloquent
{
    protected $table    = 'pertanyaans';
    public $primaryKey  = 'IdPertanyaan';
    public $timestamps  = false;
    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }
}
