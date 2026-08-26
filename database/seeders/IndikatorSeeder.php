<?php

namespace Database\Seeders;

use App\Models\Indikator;
use App\Models\SubVariabel;
use Illuminate\Database\Seeder;

class IndikatorSeeder extends Seeder
{
    /**
     * Seed the 103 indikators with their exact weights from the Excel specification.
     */
    public function run(): void
    {
        $subVariabels = SubVariabel::with('kuesioner')->get();

        // Build a lookup: "kuesioner_kode:urutan" => sub_variabel_id
        $svMap = [];
        foreach ($subVariabels as $sv) {
            $key = $sv->kuesioner->kode.':'.$sv->urutan;
            $svMap[$key] = $sv->id;
        }

        $indikators = $this->getIndikatorData();

        foreach ($indikators as $ind) {
            $svKey = $ind['kuesioner'].':'.$ind['sub_variabel_urutan'];
            Indikator::create([
                'sub_variabel_id' => $svMap[$svKey],
                'kode' => $ind['kode'],
                'pernyataan' => $ind['pernyataan'],
                'bobot_asli' => $ind['bobot_asli'],
                'urutan' => $ind['urutan'],
            ]);
        }
    }

    /**
     * All 103 indikators with their exact weights from the specification.
     *
     * @return array<int, array{kuesioner: string, sub_variabel_urutan: int, kode: string, pernyataan: string, bobot_asli: float, urutan: int}>
     */
    private function getIndikatorData(): array
    {
        return [
            // ═══ KUESIONER A: Servqual (25 indikator, 5 sub-var × 5 ind, bobot 4 each) ═══
            // Sub-var 1: Tangibles
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 1, 'kode' => 'A1', 'pernyataan' => 'Kondisi fisik kantor kecamatan bersih dan terawat', 'bobot_asli' => 4, 'urutan' => 1],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 1, 'kode' => 'A2', 'pernyataan' => 'Ruang tunggu memadai dan nyaman', 'bobot_asli' => 4, 'urutan' => 2],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 1, 'kode' => 'A3', 'pernyataan' => 'Tersedia papan informasi/display pelayanan', 'bobot_asli' => 4, 'urutan' => 3],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 1, 'kode' => 'A4', 'pernyataan' => 'Fasilitas pendukung (toilet, parkir) memadai', 'bobot_asli' => 4, 'urutan' => 4],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 1, 'kode' => 'A5', 'pernyataan' => 'Penampilan petugas rapi dan profesional', 'bobot_asli' => 4, 'urutan' => 5],
            // Sub-var 2: Reliability
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 2, 'kode' => 'A6', 'pernyataan' => 'Pelayanan diberikan sesuai SOP', 'bobot_asli' => 4, 'urutan' => 1],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 2, 'kode' => 'A7', 'pernyataan' => 'Waktu penyelesaian layanan tepat waktu', 'bobot_asli' => 4, 'urutan' => 2],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 2, 'kode' => 'A8', 'pernyataan' => 'Hasil layanan akurat dan minim kesalahan', 'bobot_asli' => 4, 'urutan' => 3],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 2, 'kode' => 'A9', 'pernyataan' => 'Prosedur layanan jelas dan mudah dipahami', 'bobot_asli' => 4, 'urutan' => 4],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 2, 'kode' => 'A10', 'pernyataan' => 'Konsistensi mutu layanan terjaga setiap hari', 'bobot_asli' => 4, 'urutan' => 5],
            // Sub-var 3: Responsiveness
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 3, 'kode' => 'A11', 'pernyataan' => 'Petugas merespons permintaan dengan cepat', 'bobot_asli' => 4, 'urutan' => 1],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 3, 'kode' => 'A12', 'pernyataan' => 'Tersedia mekanisme pengaduan yang responsif', 'bobot_asli' => 4, 'urutan' => 2],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 3, 'kode' => 'A13', 'pernyataan' => 'Pengaduan ditindaklanjuti tepat waktu', 'bobot_asli' => 4, 'urutan' => 3],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 3, 'kode' => 'A14', 'pernyataan' => 'Petugas proaktif membantu tanpa diminta', 'bobot_asli' => 4, 'urutan' => 4],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 3, 'kode' => 'A15', 'pernyataan' => 'Waktu antrean tidak berlebihan', 'bobot_asli' => 4, 'urutan' => 5],
            // Sub-var 4: Assurance
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 4, 'kode' => 'A16', 'pernyataan' => 'Petugas memiliki kompetensi teknis memadai', 'bobot_asli' => 4, 'urutan' => 1],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 4, 'kode' => 'A17', 'pernyataan' => 'Keamanan data dan dokumen warga terjaga', 'bobot_asli' => 4, 'urutan' => 2],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 4, 'kode' => 'A18', 'pernyataan' => 'Petugas bersikap sopan dan menghormati warga', 'bobot_asli' => 4, 'urutan' => 3],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 4, 'kode' => 'A19', 'pernyataan' => 'Tidak ada pungutan di luar ketentuan', 'bobot_asli' => 4, 'urutan' => 4],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 4, 'kode' => 'A20', 'pernyataan' => 'Kepercayaan masyarakat terhadap layanan tinggi', 'bobot_asli' => 4, 'urutan' => 5],
            // Sub-var 5: Empathy
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 5, 'kode' => 'A21', 'pernyataan' => 'Petugas mendengarkan keluhan warga dengan sabar', 'bobot_asli' => 4, 'urutan' => 1],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 5, 'kode' => 'A22', 'pernyataan' => 'Layanan memperhatikan kebutuhan khusus (lansia, disabilitas)', 'bobot_asli' => 4, 'urutan' => 2],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 5, 'kode' => 'A23', 'pernyataan' => 'Jam operasional layanan mudah diakses', 'bobot_asli' => 4, 'urutan' => 3],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 5, 'kode' => 'A24', 'pernyataan' => 'Petugas memberikan informasi dengan bahasa yang mudah dipahami', 'bobot_asli' => 4, 'urutan' => 4],
            ['kuesioner' => 'A', 'sub_variabel_urutan' => 5, 'kode' => 'A25', 'pernyataan' => 'Petugas menunjukkan kepedulian terhadap kepuasan warga', 'bobot_asli' => 4, 'urutan' => 5],

            // ═══ KUESIONER B: BSC (15 indikator) ═══
            // Sub-var 1: Persampahan (B1-B5) → D3
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 1, 'kode' => 'B1', 'pernyataan' => 'Program pengangkutan sampah berjalan sesuai jadwal', 'bobot_asli' => 7, 'urutan' => 1],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 1, 'kode' => 'B2', 'pernyataan' => 'Tempat pembuangan sementara (TPS) terpelihara baik', 'bobot_asli' => 7, 'urutan' => 2],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 1, 'kode' => 'B3', 'pernyataan' => 'Partisipasi masyarakat dalam pengelolaan sampah tinggi', 'bobot_asli' => 6, 'urutan' => 3],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 1, 'kode' => 'B4', 'pernyataan' => 'Program bank sampah atau daur ulang berjalan', 'bobot_asli' => 7, 'urutan' => 4],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 1, 'kode' => 'B5', 'pernyataan' => 'Penanganan sampah di wilayah kumuh efektif', 'bobot_asli' => 6, 'urutan' => 5],
            // Sub-var 2: Adminduk (B6-B10) → D3
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 2, 'kode' => 'B6', 'pernyataan' => 'Layanan administrasi kependudukan tepat waktu', 'bobot_asli' => 6, 'urutan' => 1],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 2, 'kode' => 'B7', 'pernyataan' => 'Data kependudukan akurat dan terupdate', 'bobot_asli' => 7, 'urutan' => 2],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 2, 'kode' => 'B8', 'pernyataan' => 'Layanan KTP/KK tidak ada penumpukan berkas', 'bobot_asli' => 7, 'urutan' => 3],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 2, 'kode' => 'B9', 'pernyataan' => 'Pelaporan kelahiran/kematian berjalan tertib', 'bobot_asli' => 6, 'urutan' => 4],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 2, 'kode' => 'B10', 'pernyataan' => 'Koordinasi dengan Disdukcapil berjalan lancar', 'bobot_asli' => 7, 'urutan' => 5],
            // Sub-var 3: SDM (B11-B13) → D4
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 3, 'kode' => 'B11', 'pernyataan' => 'Pelatihan digital untuk aparatur terlaksana rutin', 'bobot_asli' => 7, 'urutan' => 1],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 3, 'kode' => 'B12', 'pernyataan' => 'Aparatur mampu mengoperasikan sistem informasi', 'bobot_asli' => 7, 'urutan' => 2],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 3, 'kode' => 'B13', 'pernyataan' => 'Literasi digital aparatur meningkat dari tahun lalu', 'bobot_asli' => 6, 'urutan' => 3],
            // Sub-var 4: Anggaran (B14-B15) → D5
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 4, 'kode' => 'B14', 'pernyataan' => 'Realisasi anggaran sesuai rencana kerja', 'bobot_asli' => 50, 'urutan' => 1],
            ['kuesioner' => 'B', 'sub_variabel_urutan' => 4, 'kode' => 'B15', 'pernyataan' => 'Efisiensi penggunaan anggaran tercapai', 'bobot_asli' => 50, 'urutan' => 2],

            // ═══ KUESIONER C: Good Governance (12 indikator, 4 sub-var × 3) ═══
            // Sub-var 1: Transparansi
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 1, 'kode' => 'C1', 'pernyataan' => 'Informasi publik tersedia dan mudah diakses', 'bobot_asli' => 6.25, 'urutan' => 1],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 1, 'kode' => 'C2', 'pernyataan' => 'Laporan keuangan dipublikasikan secara berkala', 'bobot_asli' => 6.25, 'urutan' => 2],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 1, 'kode' => 'C3', 'pernyataan' => 'Proses pengambilan keputusan terbuka bagi publik', 'bobot_asli' => 6.25, 'urutan' => 3],
            // Sub-var 2: Akuntabilitas
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 2, 'kode' => 'C4', 'pernyataan' => 'Laporan kinerja disampaikan tepat waktu', 'bobot_asli' => 6.25, 'urutan' => 1],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 2, 'kode' => 'C5', 'pernyataan' => 'Tanggung jawab setiap unit kerja jelas', 'bobot_asli' => 6.25, 'urutan' => 2],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 2, 'kode' => 'C6', 'pernyataan' => 'Tindak lanjut atas temuan audit dilaksanakan', 'bobot_asli' => 6.25, 'urutan' => 3],
            // Sub-var 3: Partisipasi Publik
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 3, 'kode' => 'C7', 'pernyataan' => 'Musrenbang dilaksanakan dengan partisipasi aktif', 'bobot_asli' => 6.25, 'urutan' => 1],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 3, 'kode' => 'C8', 'pernyataan' => 'Aspirasi warga ditampung dan ditindaklanjuti', 'bobot_asli' => 6.25, 'urutan' => 2],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 3, 'kode' => 'C9', 'pernyataan' => 'Forum komunikasi warga dengan pemerintah aktif', 'bobot_asli' => 6.25, 'urutan' => 3],
            // Sub-var 4: Supremasi Hukum
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 4, 'kode' => 'C10', 'pernyataan' => 'Peraturan daerah ditegakkan secara konsisten', 'bobot_asli' => 6.25, 'urutan' => 1],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 4, 'kode' => 'C11', 'pernyataan' => 'Penegakan hukum tidak diskriminatif', 'bobot_asli' => 6.25, 'urutan' => 2],
            ['kuesioner' => 'C', 'sub_variabel_urutan' => 4, 'kode' => 'C12', 'pernyataan' => 'Mekanisme penyelesaian sengketa tersedia', 'bobot_asli' => 6.25, 'urutan' => 3],

            // ═══ KUESIONER D: SPBE (10 indikator, 3 sub-var) ═══
            // Sub-var 1: Layanan Digital Adminduk (D1-D4)
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 1, 'kode' => 'D1', 'pernyataan' => 'Sistem SIBISA terintegrasi dan berfungsi', 'bobot_asli' => 10, 'urutan' => 1],
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 1, 'kode' => 'D2', 'pernyataan' => 'Data kependudukan digital terintegrasi antar OPD', 'bobot_asli' => 10, 'urutan' => 2],
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 1, 'kode' => 'D3', 'pernyataan' => 'Layanan online adminduk tersedia dan digunakan', 'bobot_asli' => 10, 'urutan' => 3],
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 1, 'kode' => 'D4', 'pernyataan' => 'Validasi data digital mengurangi kesalahan input', 'bobot_asli' => 10, 'urutan' => 4],
            // Sub-var 2: Layanan Digital Persampahan (D5-D7)
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 2, 'kode' => 'D5', 'pernyataan' => 'Sistem monitoring persampahan digital tersedia', 'bobot_asli' => 10, 'urutan' => 1],
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 2, 'kode' => 'D6', 'pernyataan' => 'Aplikasi pelaporan sampah oleh warga tersedia', 'bobot_asli' => 10, 'urutan' => 2],
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 2, 'kode' => 'D7', 'pernyataan' => 'Data persampahan digunakan untuk perencanaan', 'bobot_asli' => 10, 'urutan' => 3],
            // Sub-var 3: Infrastruktur TIK (D8-D10)
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 3, 'kode' => 'D8', 'pernyataan' => 'Infrastruktur jaringan dan komputer memadai', 'bobot_asli' => 10, 'urutan' => 1],
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 3, 'kode' => 'D9', 'pernyataan' => 'SOP layanan digital terdokumentasi dan dijalankan', 'bobot_asli' => 10, 'urutan' => 2],
            ['kuesioner' => 'D', 'sub_variabel_urutan' => 3, 'kode' => 'D10', 'pernyataan' => 'Sistem pengaduan elektronik terintegrasi', 'bobot_asli' => 10, 'urutan' => 3],

            // ═══ KUESIONER E: PKH Makmur (20 indikator) ═══
            // Sub-var 1: F1 Identifikasi & Verifikasi (3 ind)
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 1, 'kode' => 'F1a', 'pernyataan' => 'Identifikasi sasaran PKH sesuai basis data terpadu', 'bobot_asli' => 6.7, 'urutan' => 1],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 1, 'kode' => 'F1b', 'pernyataan' => 'Verifikasi kelayakan penerima dilakukan secara berkala', 'bobot_asli' => 6.7, 'urutan' => 2],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 1, 'kode' => 'F1c', 'pernyataan' => 'Pemutakhiran data penerima terlaksana', 'bobot_asli' => 6.6, 'urutan' => 3],
            // Sub-var 2: F2 Kelengkapan Administrasi (3 ind)
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 2, 'kode' => 'F2a', 'pernyataan' => 'Berkas administrasi penerima lengkap', 'bobot_asli' => 6.7, 'urutan' => 1],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 2, 'kode' => 'F2b', 'pernyataan' => 'Dokumen pencairan bantuan terdokumentasi rapi', 'bobot_asli' => 6.7, 'urutan' => 2],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 2, 'kode' => 'F2c', 'pernyataan' => 'Laporan pertanggungjawaban disusun tepat waktu', 'bobot_asli' => 6.6, 'urutan' => 3],
            // Sub-var 3: F3 Musyawarah Kelurahan (3 ind)
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 3, 'kode' => 'F3a', 'pernyataan' => 'Musyawarah kelurahan dilaksanakan rutin', 'bobot_asli' => 6.7, 'urutan' => 1],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 3, 'kode' => 'F3b', 'pernyataan' => 'Partisipasi warga dalam musyawarah tinggi', 'bobot_asli' => 6.7, 'urutan' => 2],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 3, 'kode' => 'F3c', 'pernyataan' => 'Hasil musyawarah ditindaklanjuti secara transparan', 'bobot_asli' => 6.6, 'urutan' => 3],
            // Sub-var 4: F4 Kualitas Pendampingan (4 ind)
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 4, 'kode' => 'F4a', 'pernyataan' => 'Pendampingan penerima PKH dilakukan rutin', 'bobot_asli' => 5, 'urutan' => 1],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 4, 'kode' => 'F4b', 'pernyataan' => 'Kualitas pendampingan memenuhi standar', 'bobot_asli' => 5, 'urutan' => 2],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 4, 'kode' => 'F4c', 'pernyataan' => 'Pendamping memiliki kompetensi yang memadai', 'bobot_asli' => 5, 'urutan' => 3],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 4, 'kode' => 'F4d', 'pernyataan' => 'Rasio pendamping terhadap penerima proporsional', 'bobot_asli' => 5, 'urutan' => 4],
            // Sub-var 5: F5 Pemanfaatan SIKS-NG (2 ind)
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 5, 'kode' => 'F5a', 'pernyataan' => 'Pemanfaatan SIKS-NG untuk pendataan efektif', 'bobot_asli' => 10, 'urutan' => 1],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 5, 'kode' => 'F5b', 'pernyataan' => 'Pelaporan melalui SIKS-NG tepat waktu', 'bobot_asli' => 10, 'urutan' => 2],
            // Sub-var 6: Penerima (5 ind, bobot 20 each)
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 6, 'kode' => 'P1', 'pernyataan' => 'Penerima merasa terbantu oleh program PKH', 'bobot_asli' => 20, 'urutan' => 1],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 6, 'kode' => 'P2', 'pernyataan' => 'Penerima memahami hak dan kewajibannya', 'bobot_asli' => 20, 'urutan' => 2],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 6, 'kode' => 'P3', 'pernyataan' => 'Bantuan diterima tepat waktu dan sesuai jumlah', 'bobot_asli' => 20, 'urutan' => 3],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 6, 'kode' => 'P4', 'pernyataan' => 'Pendampingan dirasakan bermanfaat oleh penerima', 'bobot_asli' => 20, 'urutan' => 4],
            ['kuesioner' => 'E', 'sub_variabel_urutan' => 6, 'kode' => 'P5', 'pernyataan' => 'Proses graduasi mandiri berjalan dengan baik', 'bobot_asli' => 20, 'urutan' => 5],

            // ═══ KUESIONER F: Ketertiban Umum (21 indikator) ═══
            // Sub-var 1: G1 Kepatuhan Warga (4 ind, bobot 5 each)
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 1, 'kode' => 'G1a', 'pernyataan' => 'Kepatuhan warga terhadap perda ketertiban umum', 'bobot_asli' => 5, 'urutan' => 1],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 1, 'kode' => 'G1b', 'pernyataan' => 'Tingkat pelanggaran ketertiban menurun', 'bobot_asli' => 5, 'urutan' => 2],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 1, 'kode' => 'G1c', 'pernyataan' => 'Kesadaran warga terhadap aturan tinggi', 'bobot_asli' => 5, 'urutan' => 3],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 1, 'kode' => 'G1d', 'pernyataan' => 'Sosialisasi peraturan dilaksanakan rutin', 'bobot_asli' => 5, 'urutan' => 4],
            // Sub-var 2: G2 Operasi Penertiban (4 ind, bobot 5 each)
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 2, 'kode' => 'G2a', 'pernyataan' => 'Operasi penertiban dilaksanakan secara berkala', 'bobot_asli' => 5, 'urutan' => 1],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 2, 'kode' => 'G2b', 'pernyataan' => 'Koordinasi dengan instansi terkait berjalan baik', 'bobot_asli' => 5, 'urutan' => 2],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 2, 'kode' => 'G2c', 'pernyataan' => 'Tindakan penertiban proporsional dan humanis', 'bobot_asli' => 5, 'urutan' => 3],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 2, 'kode' => 'G2d', 'pernyataan' => 'Laporan hasil penertiban terdokumentasi', 'bobot_asli' => 5, 'urutan' => 4],
            // Sub-var 3: G3 Kondisi Ruang Publik (3 ind)
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 3, 'kode' => 'G3a', 'pernyataan' => 'Kondisi ruang publik bersih dan tertib', 'bobot_asli' => 6.7, 'urutan' => 1],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 3, 'kode' => 'G3b', 'pernyataan' => 'Tidak ada PKL liar di lokasi terlarang', 'bobot_asli' => 6.7, 'urutan' => 2],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 3, 'kode' => 'G3c', 'pernyataan' => 'Fasilitas umum terpelihara dan aman', 'bobot_asli' => 6.6, 'urutan' => 3],
            // Sub-var 4: G4 Tertib Sosial & Usaha (2 ind, bobot 10 each)
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 4, 'kode' => 'G4a', 'pernyataan' => 'Perizinan usaha ditegakkan sesuai ketentuan', 'bobot_asli' => 10, 'urutan' => 1],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 4, 'kode' => 'G4b', 'pernyataan' => 'Ketertiban sosial di lingkungan usaha terjaga', 'bobot_asli' => 10, 'urutan' => 2],
            // Sub-var 5: G5 Partisipasi Warga (3 ind)
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 5, 'kode' => 'G5a', 'pernyataan' => 'Partisipasi warga dalam menjaga ketertiban tinggi', 'bobot_asli' => 6.7, 'urutan' => 1],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 5, 'kode' => 'G5b', 'pernyataan' => 'Sistem siskamling/ronda berjalan aktif', 'bobot_asli' => 6.7, 'urutan' => 2],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 5, 'kode' => 'G5c', 'pernyataan' => 'Pelaporan gangguan ketertiban oleh warga aktif', 'bobot_asli' => 6.6, 'urutan' => 3],
            // Sub-var 6: Warga (5 ind, bobot 20 each)
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 6, 'kode' => 'W1', 'pernyataan' => 'Warga merasa aman di lingkungannya', 'bobot_asli' => 20, 'urutan' => 1],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 6, 'kode' => 'W2', 'pernyataan' => 'Kondisi ketertiban di lingkungan baik', 'bobot_asli' => 20, 'urutan' => 2],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 6, 'kode' => 'W3', 'pernyataan' => 'Gangguan ketertiban jarang terjadi', 'bobot_asli' => 20, 'urutan' => 3],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 6, 'kode' => 'W4', 'pernyataan' => 'Respons aparat terhadap gangguan cepat', 'bobot_asli' => 20, 'urutan' => 4],
            ['kuesioner' => 'F', 'sub_variabel_urutan' => 6, 'kode' => 'W5', 'pernyataan' => 'Kepuasan warga terhadap kondisi ketertiban tinggi', 'bobot_asli' => 20, 'urutan' => 5],
        ];
    }
}
