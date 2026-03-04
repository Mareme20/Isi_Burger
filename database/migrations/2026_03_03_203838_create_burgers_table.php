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
    Schema::create('burgers', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->decimal('prix', 10, 2);
        $table->text('description')->nullable();
        $table->string('image')->nullable();
        $table->integer('stock')->default(0);
        $table->boolean('is_archived')->default(false);

        $table->foreignId('category_id')
              ->constrained()
              ->onDelete('cascade');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burgers');
    }
};
