<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();  // Unique ID for each playlist
            $table->string('name');  // Name of the playlist
            $table->text('description')->nullable(); 
            $table->string('image');  
            $table->unsignedBigInteger('created_by');  // ID of the admin who created it
            $table->timestamps();  
            // Foreign key constraint for the user/admin who created it
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlists');
    }
}
