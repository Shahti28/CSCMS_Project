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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();

                $table->foreignId('student_id')
                      ->constrained()
                      ->onDelete('cascade');

                $table->enum('type', [
                    'tuition',
                    'library_fine',
                    'miscellaneous'
                ])->default('tuition');

                $table->decimal('amount', 10, 2);

                $table->text('description')->nullable();

                $table->date('payment_date')->nullable();

                $table->string('status')->default('completed');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
