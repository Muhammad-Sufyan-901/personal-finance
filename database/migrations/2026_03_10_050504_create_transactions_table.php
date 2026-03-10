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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Membuat relasi ke tabel users. Jika user dihapus, transaksinya ikut terhapus (cascade)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Tipe transaksi: pemasukan atau pengeluaran
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            
            // Nominal uang. Pakai decimal agar aman untuk perhitungan keuangan (15 digit, 2 angka di belakang koma)
            $table->decimal('amount', 15, 2);
            
            // Keterangan transaksi
            $table->string('description');

            // Bukti Transaksi (Struk belanja / Foto)
            $table->string('proof')->nullable();
            
            // Tanggal transaksi
            $table->date('date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
