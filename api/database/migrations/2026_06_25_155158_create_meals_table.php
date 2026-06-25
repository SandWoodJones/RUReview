<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_menu_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('protein');
            $table->string('protein_vegan');
            $table->string('beans');
            $table->string('carb_extra');
            $table->string('salad_extra');
            $table->string('dessert');
            $table->timestamps();

            $table->unique(['daily_menu_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
