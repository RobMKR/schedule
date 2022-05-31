<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_STAFF])->default(\App\Models\User::ROLE_STAFF); // Ideally should be separate roles / permissions table, and user_role relational table :)
            $table->timestamps();
        });

        \App\Models\User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@invygo.com',
            'password' => \Illuminate\Support\Facades\Hash::make(env('ADMIN_PASSWORD')),
            'role' => \App\Models\User::ROLE_ADMIN,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
