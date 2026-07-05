<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_security_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('security_question_id')->constrained()->onDelete('cascade');
            $table->string('answer');
            $table->timestamps();

            $table->unique(['user_id', 'security_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_security_answers');
    }
};
