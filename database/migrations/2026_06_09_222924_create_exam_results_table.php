<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {

            $table->id();

            $table->foreignId('exam_session_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('table_number');

            $table->string('full_name');

            $table->unsignedTinyInteger('lesen');
            $table->unsignedTinyInteger('hoeren');
            $table->unsignedTinyInteger('schreiben');
            $table->unsignedTinyInteger('sprechen');

            $table->string('mention')->nullable();

            $table->unique([
                'exam_session_id',
                'table_number'
            ]);

            $table->timestamps();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};