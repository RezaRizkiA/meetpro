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
        Schema::create('expert_skill', function (Blueprint $table) {
            // Relasi ke Expert
            $table->foreignId('expert_id')->constrained('experts')->onDelete('cascade');

            // Relasi ke Skill (Level 3)
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');

            // Mencegah duplikat (Expert A tidak bisa input Skill SEO dua kali)
            $table->primary(['expert_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_skill');
    }
};
