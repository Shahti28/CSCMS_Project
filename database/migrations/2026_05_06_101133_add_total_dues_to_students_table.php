<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('students', 'total_dues')) {
            Schema::table('students', function (Blueprint $table) {
                $table->decimal('total_dues', 10, 2)->default(0)->after('enrollment_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('students', 'total_dues')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('total_dues');
            });
        }
    }
};

