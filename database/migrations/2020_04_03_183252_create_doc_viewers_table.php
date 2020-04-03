<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocViewersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_viewers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('doc_id');
            $table->unsignedBigInteger('user_id');


            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('doc_id')
                ->references('id')
                ->on('docs');

            $table->unique(['user_id', 'doc_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doc_viewers');
    }
}
