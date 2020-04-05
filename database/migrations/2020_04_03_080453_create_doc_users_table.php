<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doc_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('doc_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');


            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('doc_id')
                ->references('id')
                ->on('docs');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');

            $table->unique(['user_id', 'doc_id']);

            $table->index(['doc_id', 'user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doc_users');
    }
}
