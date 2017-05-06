<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class library_data extends Model implements Authenticatable
{
    //
    use \Illuminate\Auth\Authenticatable;

    protected $table = 'library_datas';

    public function libraryCategory()
    {
        return $this->belongsTo('App\library_categories','category');
    }

    public function librarySubCategory()
    {
        return $this->belongsTo('App\library_subcategory','subcategory');
    }
}
