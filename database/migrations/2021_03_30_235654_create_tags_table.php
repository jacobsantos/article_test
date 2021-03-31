<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreign("owner_id")->references("id")->on("users");
            $table->timestamps();
            // Generally unwise to do this since the storage requirements could get unwieldy with an arbitrary length
            // string. Fortunately, not doing this for performance... although it could help with it
            $table->unique(['name', 'owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
