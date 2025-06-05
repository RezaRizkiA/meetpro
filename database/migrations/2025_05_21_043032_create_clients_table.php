<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->nullable()->constrained('users')->onDelete('cascade');
            $table->string('section_hero')->nullable();
            $table->string('banner_title')->nullable();
            $table->text('banner_desc')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_photo')->nullable();
            $table->string('banner_background')->nullable();
            $table->string('banner_footer')->nullable();
            $table->text('banner_footer_desc')->nullable();
            $table->string('color')->nullable();
            $table->string('logo')->nullable();
            $table->string('slug_page')->unique()->nullable();
            $table->text('expertise_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
