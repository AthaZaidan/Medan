<?php

namespace Database\Seeders;

use App\Models\Kuesioner;
use App\Models\SubVariabel;
use Illuminate\Database\Seeder;

class SubVariabelSeeder extends Seeder
{
    /**
     * Seed the 28 sub-variabels exactly as specified.
     */
    public function run(): void
    {
        $kuesionerMap = Kuesioner::pluck('id', 'kode')->toArray();

        $subVariabels = [
            // Kuesioner A → D1 (5 sub-variabel × 5 indikator = 25)
            ['kuesioner' => 'A', 'nama' => 'Tangibles — Bukti Fisik Sarana & Fasilitas Layanan', 'dimensi_kode' => 'D1', 'bobot_subtotal' => 20, 'urutan' => 1],
            ['kuesioner' => 'A', 'nama' => 'Reliability — Keandalan & Konsistensi Layanan', 'dimensi_kode' => 'D1', 'bobot_subtotal' => 20, 'urutan' => 2],
            ['kuesioner' => 'A', 'nama' => 'Responsiveness — Ketanggapan & Kecepatan Respons', 'dimensi_kode' => 'D1', 'bobot_subtotal' => 20, 'urutan' => 3],
            ['kuesioner' => 'A', 'nama' => 'Assurance — Jaminan Kompetensi, Keamanan & Kepercayaan', 'dimensi_kode' => 'D1', 'bobot_subtotal' => 20, 'urutan' => 4],
            ['kuesioner' => 'A', 'nama' => 'Empathy — Kepedulian & Perhatian terhadap Kebutuhan Warga', 'dimensi_kode' => 'D1', 'bobot_subtotal' => 20, 'urutan' => 5],

            // Kuesioner B → D3, D4, D5
            ['kuesioner' => 'B', 'nama' => 'Proses Bisnis Internal — Program Persampahan', 'dimensi_kode' => 'D3', 'bobot_subtotal' => 33, 'urutan' => 1],
            ['kuesioner' => 'B', 'nama' => 'Proses Bisnis Internal — Program Adminduk', 'dimensi_kode' => 'D3', 'bobot_subtotal' => 33, 'urutan' => 2],
            ['kuesioner' => 'B', 'nama' => 'Pembelajaran & Pertumbuhan — SDM & Literasi Digital', 'dimensi_kode' => 'D4', 'bobot_subtotal' => 20, 'urutan' => 3],
            ['kuesioner' => 'B', 'nama' => 'Anggaran & Efisiensi Keuangan', 'dimensi_kode' => 'D5', 'bobot_subtotal' => 100, 'urutan' => 4],

            // Kuesioner C → D2 (4 sub-variabel × 3 = 12)
            ['kuesioner' => 'C', 'nama' => 'Transparansi', 'dimensi_kode' => 'D2', 'bobot_subtotal' => 25, 'urutan' => 1],
            ['kuesioner' => 'C', 'nama' => 'Akuntabilitas', 'dimensi_kode' => 'D2', 'bobot_subtotal' => 18.75, 'urutan' => 2],
            ['kuesioner' => 'C', 'nama' => 'Partisipasi Publik', 'dimensi_kode' => 'D2', 'bobot_subtotal' => 18.75, 'urutan' => 3],
            ['kuesioner' => 'C', 'nama' => 'Supremasi Hukum', 'dimensi_kode' => 'D2', 'bobot_subtotal' => 12.5, 'urutan' => 4],

            // Kuesioner D → D4 (3 sub-variabel)
            ['kuesioner' => 'D', 'nama' => 'Layanan Digital Adminduk — SIBISA & Integrasi Data', 'dimensi_kode' => 'D4', 'bobot_subtotal' => 40, 'urutan' => 1],
            ['kuesioner' => 'D', 'nama' => 'Layanan Digital Persampahan', 'dimensi_kode' => 'D4', 'bobot_subtotal' => 30, 'urutan' => 2],
            ['kuesioner' => 'D', 'nama' => 'Infrastruktur TIK, SOP Digital & Pengaduan Elektronik', 'dimensi_kode' => 'D4', 'bobot_subtotal' => 30, 'urutan' => 3],

            // Kuesioner E → D6 (6 sub-variabel = 20 indikator)
            ['kuesioner' => 'E', 'nama' => 'Aparatur: E1 Identifikasi & Verifikasi Sasaran', 'dimensi_kode' => 'D6', 'bobot_subtotal' => 20, 'urutan' => 1],
            ['kuesioner' => 'E', 'nama' => 'Aparatur: E2 Kelengkapan Administrasi', 'dimensi_kode' => 'D6', 'bobot_subtotal' => 20, 'urutan' => 2],
            ['kuesioner' => 'E', 'nama' => 'Aparatur: E3 Musyawarah Kelurahan', 'dimensi_kode' => 'D6', 'bobot_subtotal' => 20, 'urutan' => 3],
            ['kuesioner' => 'E', 'nama' => 'Aparatur: E4 Kualitas Pendampingan Penerima', 'dimensi_kode' => 'D6', 'bobot_subtotal' => 20, 'urutan' => 4],
            ['kuesioner' => 'E', 'nama' => 'Aparatur: E5 Pemanfaatan SIKS-NG Digital', 'dimensi_kode' => 'D6', 'bobot_subtotal' => 20, 'urutan' => 5],
            ['kuesioner' => 'E', 'nama' => 'Penerima Manfaat PKH Makmur (Pengalaman Penerima)', 'dimensi_kode' => 'D6', 'bobot_subtotal' => 100, 'urutan' => 6],

            // Kuesioner F → D7 (6 sub-variabel = 21 indikator)
            ['kuesioner' => 'F', 'nama' => 'Aparatur: F1 Kepatuhan Warga', 'dimensi_kode' => 'D7', 'bobot_subtotal' => 20, 'urutan' => 1],
            ['kuesioner' => 'F', 'nama' => 'Aparatur: F2 Operasi Penertiban Satpol PP', 'dimensi_kode' => 'D7', 'bobot_subtotal' => 20, 'urutan' => 2],
            ['kuesioner' => 'F', 'nama' => 'Aparatur: F3 Kondisi Ruang Publik', 'dimensi_kode' => 'D7', 'bobot_subtotal' => 20, 'urutan' => 3],
            ['kuesioner' => 'F', 'nama' => 'Aparatur: F4 Tertib Sosial & Usaha', 'dimensi_kode' => 'D7', 'bobot_subtotal' => 20, 'urutan' => 4],
            ['kuesioner' => 'F', 'nama' => 'Aparatur: F5 Partisipasi Warga', 'dimensi_kode' => 'D7', 'bobot_subtotal' => 20, 'urutan' => 5],
            ['kuesioner' => 'F', 'nama' => 'Warga/Tokoh Masyarakat: Kondisi Ketertiban (Persepsi Warga)', 'dimensi_kode' => 'D7', 'bobot_subtotal' => 100, 'urutan' => 6],
        ];

        foreach ($subVariabels as $sv) {
            SubVariabel::create([
                'kuesioner_id' => $kuesionerMap[$sv['kuesioner']],
                'nama' => $sv['nama'],
                'dimensi_kode' => $sv['dimensi_kode'],
                'bobot_subtotal' => $sv['bobot_subtotal'],
                'urutan' => $sv['urutan'],
            ]);
        }
    }
}
