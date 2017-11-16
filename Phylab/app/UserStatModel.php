<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStatModel extends Model
{
    //
    protected  $table = 'UserStat';
    protected  $fillable = ['uid','path','type'];

}
