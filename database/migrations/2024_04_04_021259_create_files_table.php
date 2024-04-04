<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade');
            $table->mediumText('name')->nullable();
            $table->string('size', 30)->nullable();
            $table->string('mimetype')->nullable();
            $table->string('extension', 20)->nullable();
            $table->boolean('is_thumb')->nullable();
            $table->mediumText('path')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('visibility', 15)->nullable()->default('private');
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
        Schema::dropIfExists('files');
    }
}
