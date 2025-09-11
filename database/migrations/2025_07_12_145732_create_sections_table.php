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
        Schema::create('sections', function (Blueprint $table) {
            $table->unsignedBigInteger('sigaId')->primary();
            $table->unsignedBigInteger("course_id");
            $table->unSignedBigInteger("professor_id")->nullable();
            $table->foreign("course_id")->references("sigaId")->on("courses");
            $table->foreign("professor_id")->references("account")->on("professors");       
            $table->boolean("status")->default(1);
            $table->timestamps();
          /*
          Indexar los campos
            $table->integer("term");
            $table->integer("year");
            $table->index('year');
            $table->index('term');
            $table->index(['term', 'year'], 'idx_term_year');
        */ 
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
