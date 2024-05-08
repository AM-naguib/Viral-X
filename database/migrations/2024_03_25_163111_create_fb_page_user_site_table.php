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
        Schema::create('fb_page_user_site', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fb_page_id');
            $table->unsignedBigInteger('user_site_id');
            $table->timestamps();

            // Define foreign keys
            $table->foreign('fb_page_id')->references('id')->on('fb_pages')->onDelete('cascade');
            $table->foreign('user_site_id')->references('id')->on('user_sites')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fb_page_user_site');
    }
};
