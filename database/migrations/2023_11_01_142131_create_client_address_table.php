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
        Schema::create('client_address', function (Blueprint $table) {
            $table->id();
            $table->string('formatted_address');
            $table->decimal('lat', 11, 8)->nullable();
            $table->decimal('lng', 10, 8)->nullable();
            $table->foreignId('client_id')->index();
            $table->foreignId('type_id')->index();
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('type_id')->references('id')->on('address_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_address');
    }
};
