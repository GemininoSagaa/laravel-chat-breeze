<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('content');
            $table->boolean('read')->default(false);
            $table->timestamps();
            
            // Un mensaje puede ser para un usuario o para un grupo, pero no ambos
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['sender_id', 'group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};