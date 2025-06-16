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
        Schema::create('responseSubmits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("submitId");
            $table->unsignedBigInteger("optionId");
            $table->foreign("submitId")->references("id")->on("surveySubmits");
            $table->foreign("optionId")->references("id")->on("questionOptions");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_submits');
    }
};
