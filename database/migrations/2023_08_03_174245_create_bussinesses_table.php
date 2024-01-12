->nullable()<?php

            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
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
                    Schema::create('bussinesses', function (Blueprint $table) {
                        $table->id();
                        $table->string("name")->nullable();
                        $table->string("bussiness_name")->nullable();
                        $table->string('email')->nullable();
                        $table->string('password')->nullable();
                        $table->string('phone')->nullable();
                        $table->string('photo')->nullable();
                        $table->boolean('delivery')->nullable();
                        $table->boolean('has_user_account')->nullable();
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
                    Schema::dropIfExists('bussinesses');
                }
            };
