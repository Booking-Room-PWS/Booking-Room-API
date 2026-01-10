<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log', function (Blueprint $table) {
            $table->increments('log_id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('log_method');
            $table->string('log_url');
            $table->string('log_ip')->nullable();
            $table->text('log_request')->nullable();
            $table->text('log_response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log');
    }
};