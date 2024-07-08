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
        Schema::create('checkpoints', function (Blueprint $table) {
            $table->id();
            $table->string('checkpoint_id');
            $table->string('name');
            $table->string('chief');
            $table->string('chief_phone')->nullable();
            $table->string('station');
            $table->string('division');
            $table->string('bureau');
            $table->string('type')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('approval')->default(0);
            $table->timestamps();

            // Add a unique constraint for checkpoint_id and created_at date
            $table->unique(['checkpoint_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkpoints');
    }
};
