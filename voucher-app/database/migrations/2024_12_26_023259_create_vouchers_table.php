<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('username')->unique(); // Unique username for the voucher
            $table->string('password'); // Password for the voucher
            $table->string('status')->default('inactive'); // Status: active/inactive
            $table->string('limit_uptime')->nullable(); // Uptime limit, e.g., '1d'
            $table->integer('price')->default(0); // Price of the voucher
            $table->string('profile')->nullable(); // Optional: bandwidth or package type
            $table->string('comment')->nullable(); // Notes or additional metadata
            $table->unsignedBigInteger('agent_id'); // Foreign key linking to agents
            $table->timestamps(); // Created and updated timestamps

            // Add foreign key constraint
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
