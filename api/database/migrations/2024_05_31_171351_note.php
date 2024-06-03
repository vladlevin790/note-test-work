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
        Schema::create('note', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->default("user");
            $table->string('company')->nullable();
            $table->string('phone-number')->default('null');
            $table->string('email')->default('null');
            $table->timestamp("date_birth")->nullable();
            $table->string('path_to_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note');
    }
};
