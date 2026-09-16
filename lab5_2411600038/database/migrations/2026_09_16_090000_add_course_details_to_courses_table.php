<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('courses', 'name')) {
            return;
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('code')->unique()->after('name');
            $table->string('category')->after('code');
            $table->unsignedInteger('enrolled_students')->default(0)->after('category');
            $table->unsignedInteger('capacity')->default(40)->after('enrolled_students');
            $table->unsignedInteger('units')->default(3)->after('capacity');
            $table->string('instructor')->after('units');
            $table->string('department')->after('instructor');
            $table->text('description')->nullable()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn([
                'name',
                'code',
                'category',
                'enrolled_students',
                'capacity',
                'units',
                'instructor',
                'department',
                'description',
            ]);
        });
    }
};