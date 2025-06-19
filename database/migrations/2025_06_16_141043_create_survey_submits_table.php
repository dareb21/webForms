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
        Schema::create('survey_submits', function (Blueprint $table) {
            $table->id();
            $table->date("DateSubmmited");
            $table->foreignId('survey_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->text("observations")->nullable();
            //$table->foreignId('teacher_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_submits');
    }
};
