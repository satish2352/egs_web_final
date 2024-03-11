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
        Schema::create('labour', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('gender');
            $table->string('date_of_birth');
            $table->string('district_id');
            $table->string('taluka_id');
            $table->string('village_id');
            $table->string('mobile_number');
            $table->string('landline_number')->default('null');
            $table->string('mgnrega_card_id');
            $table->string('location_id');
            $table->string('aadhar_image')->default('null');
            $table->string('pancard_image')->default('null');
            $table->string('profile_image')->default('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labour');
    }
};
