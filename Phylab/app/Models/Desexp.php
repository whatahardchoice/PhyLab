<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Desexp extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'desexps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'link', 'mdfile'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = [];

    /*     public function stars(){
            return $this->hasMany('App\Models\Star','user_id','id');
        }
     */

}