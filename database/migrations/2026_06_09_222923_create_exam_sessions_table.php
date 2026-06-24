<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('center_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->enum('level', [
                'A1',
                'A2',
                'B1',
                'B2',
                'C1',
                'C2'
            ]);

            
            $table->unsignedInteger('module_max_score');
            $table->unsignedInteger('passing_percentage');
            $table->date('exam_date');  
            $table->boolean('published')
                ->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};