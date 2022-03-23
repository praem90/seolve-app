<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostAccountAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_account_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_account_id')->constrained();
            $table->foreignIdFor(\App\Models\PostAsset::class)->constrained();
            $table->string('social_media_asset_id')->nullable();
            $table->string('url')->nullable();
            $table->string('status')->nullable();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('post_account_assets');
    }
}
