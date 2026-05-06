<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $books = DB::table('books')
            ->select('id', 'nomor_buku', 'barcode')
            ->orderBy('id')
            ->get();

        $year = now()->format('Y');
        $sequence = 1;

        foreach ($books as $book) {
            $updates = [];

            if (empty($book->nomor_buku)) {
                do {
                    $nomor = sprintf('SPH-BK-%s-%06d', $year, $sequence++);
                    $exists = DB::table('books')->where('nomor_buku', $nomor)->exists();
                } while ($exists);
                $updates['nomor_buku'] = $nomor;
            }

            if (empty($book->barcode)) {
                do {
                    $barcode = 'SPH' . now()->format('ymd') . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
                    $exists = DB::table('books')->where('barcode', $barcode)->exists();
                } while ($exists);
                $updates['barcode'] = $barcode;
            }

            if (!empty($updates)) {
                $updates['updated_at'] = now();
                DB::table('books')->where('id', $book->id)->update($updates);
            }
        }
    }

    public function down(): void
    {
        // no-op: preserve generated identifiers
    }
};

