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
        Schema::create('court_cases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('case_number')->unique();
            $table->string('applicant');
            $table->string('respondent');
            $table->enum('case_type', ['Civil Suit','Civil Appeal','Suit', 'Criminal', 'Family', 'Labor','Arbitration','Summary Suit','Special Case','PPC', 'Other']);
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->date('hearing_date')->nullable();
            $table->text('notes')->nullable();

            $table->uuid('user_id')->nullable();        // Advocate
            $table->uuid('district_id');
            $table->uuid('court_id');
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_cases');
    }
};
