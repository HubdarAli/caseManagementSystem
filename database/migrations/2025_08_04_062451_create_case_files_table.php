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
        Schema::create('case_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('court_case_id');
            $table->string('file_path'); // e.g., storage/app/cases/file.pdf
            $table->string('original_name')->nullable(); // For user-friendly display
            $table->string('mime_type')->nullable(); // e.g., application/pdf
            $table->string('size')->nullable(); // Size in bytes
            $table->enum('status', ['Pending', 'Reviewed', 'Archived'])->default('Pending');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('case_files');
    }
};
