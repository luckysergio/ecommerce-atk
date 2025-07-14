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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jenis')->constrained('jenis')->onDelete('cascade');
            $table->foreignId('id_merk')->constrained('merks')->onDelete('cascade');
            $table->string('nama');
            $table->enum('status', ['kosong','tersedia']);
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->integer('qty');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
