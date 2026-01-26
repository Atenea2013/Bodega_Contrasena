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
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            // ESTAS SON LAS COLUMNAS QUE FALTAN:
            $table->string('site_name');
            $table->string('account_user');
            $table->text('password_encrypted'); // Usamos text porque el cifrado es largo
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
