<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groupe_users', function (Blueprint $table) {
            $table->id();
            $table->string('role',25);// admin,user
            $table->string('status',25);// approvle,pending
            $table->string('token',1024)->nullable();// approvle,pending
            $table->timestamp('token_expire_date')->nullable();// approvle,pending
            $table->timestamp('token_used')->nullable();// approvle,pending
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('group_id')->constrained('groupes');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('created_at')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupe_users');
    }
};
