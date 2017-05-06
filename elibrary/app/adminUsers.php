<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class adminUsers extends Model implements Authenticatable
{
    //
    use \Illuminate\Auth\Authenticatable;
}
