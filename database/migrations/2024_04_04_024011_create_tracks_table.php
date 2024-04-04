<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 40)->index();
            $table->string('isrc')->unique();
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade');
            $table->string('title');
            $table->dateTime('release_date')->nullable();
            $table->string('duration')->nullable();
            $table->string('spotify_url')->nullable();
            $table->boolean('available_in_brazil');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign(Company::class);
        });

        Schema::dropIfExists('tracks');
    }
}
