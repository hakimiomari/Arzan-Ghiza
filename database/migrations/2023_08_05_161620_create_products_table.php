<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bussinesses_id')->constrained();
            $table->string('tittle')->nullable();
            $table->string('description')->nullable();
            $table->float('price')->nullable();
            $table->float('discount_price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('expires_date')->nullable();
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
