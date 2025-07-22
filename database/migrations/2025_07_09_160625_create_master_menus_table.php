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
        Schema::create('master_menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->integer('urutan')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->enum('route_type', ['admin', 'public', 'api'])->default('public');
            $table->string('controller_class')->nullable();
            $table->string('view_path')->nullable();
            $table->json('middleware_list')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('parent_id')->references('id')->on('master_menus')->onDelete('cascade');
            
            // Indexes
            $table->index(['parent_id', 'urutan']);
            $table->index('slug');
            $table->index('route_type');
            $table->index('controller_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_menus');
    }
};
