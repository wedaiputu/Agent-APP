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
            $table->id();
            $table->integer('quantity')->default(1);
            $table->string('username');
            $table->string('password');
            $table->integer('price');
            $table->integer('uptime_limit');
            $table->string('barcode');
            $table->unsignedBigInteger('agent_id');  // Foreign key to agents
            $table->timestamps();

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
