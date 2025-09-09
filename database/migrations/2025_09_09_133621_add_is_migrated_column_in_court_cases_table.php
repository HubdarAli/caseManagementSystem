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
        Schema::table('court_cases', function (Blueprint $table) {
            //district_id column make nullable
            // case_number unique constraint removed
            $table->dropUnique(['case_number']);
            
            // $table->string('title')->nullable()->change();
            $table->string('case_type')->nullable()->change();
            $table->uuid('district_id')->nullable(true)->change();
            $table->uuid('court_id')->nullable(true)->change();
            $table->tinyInteger('is_migrated')->default(0)->after('notes');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('court_cases', function (Blueprint $table) {
            $table->unique('case_number');
            // $table->string('title')->nullable(false)->change();
            $table->string('case_type')->nullable(false)->change();
            $table->uuid('district_id')->nullable(false)->change();
            $table->uuid('court_id')->nullable(false)->change();
            $table->dropColumn('is_migrated');
        });
    }
};
