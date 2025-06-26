<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('lang.table_name'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('locale')->unique()->index();
            $table->boolean('is_default')->default(false)->index();
            $table->boolean('is_admin_active')->default(false);
            $table->boolean('is_frontend_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('lang.table_name'));
    }
};
