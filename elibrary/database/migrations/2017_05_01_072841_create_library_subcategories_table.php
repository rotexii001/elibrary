<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibrarySubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_subcategories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subcategory_name');
            $table->string('parent_id');
            $table->integer('status');
            $table->string('created_by');
            $table->unique('subcategory_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('library_subcategories');
    }
}
