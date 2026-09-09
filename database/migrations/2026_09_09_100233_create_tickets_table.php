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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

        $table->string('subject');
        $table->text('description');

        $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
        $table->enum('priority', ['low', 'medium', 'high'])->nullable(); // AI will fill this
        $table->string('ai_summary')->nullable(); // AI will fill this

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
