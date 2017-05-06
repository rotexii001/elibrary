<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class library_categories extends Model implements Authenticatable
{
    //
    use \Illuminate\Auth\Authenticatable;

    public function getCategories()
    {
        return $this->select('*')->where([['id','>',0],['status','=',1]])->get();
    }

    public function LibraryData()
    {
        return $this->hasMany('App\library_data','category');
    }

    public function LibraryCategoryChild()
    {
        return $this->hasMany('App\library_subcategory','parent_id');
    }

}
