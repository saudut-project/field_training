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
        Schema::create('departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->string('name');
            $table->unsignedBigInteger('college_id');
            $table->unsignedBigInteger('chairperson_id');
            $table->timestamps();
        
            $table->foreign('college_id')->references('college_id')->on('colleges')->onDelete('cascade');
            $table->foreign('chairperson_id')->references('chairperson_id')->on('chairpersons')->onDelete('cascade');
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
