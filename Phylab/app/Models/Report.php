<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $table = 'reports';
    protected $fillable = ['experiment_id','experiment_name','prepare_link','experiment_tag','relatedArticle'];
    public function stars(){
        return $this->hasMany('App\Models\Star','report_id','experiment_id');
    }
}
