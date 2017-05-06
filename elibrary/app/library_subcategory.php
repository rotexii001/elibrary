<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class library_subcategory extends Model implements Authenticatable
{
    //
    use \Illuminate\Auth\Authenticatable;

    protected $table = 'library_subcategories';
    
    public function getSubCategories()
    {
        return $this->select('*')->where([['id','>',0],['status','=',1]])->get();
    }

    public function LibraryDataSubCategory()
    {
        return $this->hasMany('App\library_data','subcategory');
    }
}
