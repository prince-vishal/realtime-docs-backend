<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('data')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->timestamps();


            $table->foreign('owner_id')
                ->references('id')
                ->on('users');


            $table->index(['owner_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs');
    }
}
