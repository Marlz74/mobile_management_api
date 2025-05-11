<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lock_histories', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->string('action'); 
            $table->string('reason')->nullable();
            $table->string('performed_by')->nullable();
            $table->timestamp('action_at');
            $table->foreign('device_id')->references('device_id')->on('devices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lock_histories');
    }
};
