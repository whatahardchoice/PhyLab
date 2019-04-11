<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    //表名为stars
    protected $table = 'stars';
    protected $fillable = ['name','link','id','report_id', 'user_id'];
    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function report(){
        return $this->belongsTo('App\Models\Report','report_id','experiment_id');
    }
}
