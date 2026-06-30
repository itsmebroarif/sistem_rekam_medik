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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->dateTime('record_date');
            $table->text('subjective');
            $table->text('objective');
            $table->text('assessment');
            $table->text('planning');
            $table->string('diagnosis');
            $table->text('treatment')->nullable();
            $table->enum('bpjs_sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->enum('satusehat_sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
