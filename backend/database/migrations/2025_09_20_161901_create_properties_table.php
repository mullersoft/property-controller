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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table
                ->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 10, 2);
            $table
                ->enum('status', ['available', 'assigned', 'maintenance', 'retired'])
                ->default('available');
            $table->string('serial_number')->unique()->nullable();
            $table->string('model_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->decimal('current_value', 10, 2)->nullable();

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
        Schema::dropIfExists('properties');
    }
};
