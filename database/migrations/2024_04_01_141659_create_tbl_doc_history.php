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
        Schema::create('tbl_doc_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roles_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('grampanchayat_document_id');
            $table->unsignedBigInteger('is_approved');
            $table->unsignedBigInteger('doc_reason_id');
            $table->string('other_remark')->nullable();
            $table->string('is_deleted')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_doc_history');
    }
};