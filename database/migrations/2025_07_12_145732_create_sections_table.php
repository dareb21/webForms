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
            $table->id();
            $table->string("code");
            $table->foreignId("course_id")->constrained();
            $table->foreignId("user_id")->constrained();
            $table->boolean("status");
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
