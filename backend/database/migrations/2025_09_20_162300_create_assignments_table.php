<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('property_id')
                ->constrained('properties')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table
                ->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->date('assigned_date');
            $table->date('return_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
