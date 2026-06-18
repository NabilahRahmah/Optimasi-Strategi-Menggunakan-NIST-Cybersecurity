<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;

class PertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pertanyaans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $pertanyaans = [];

        // Sub Kategori 1.1.1 — Menetapkan dan mengomunikasikan prioritas untuk misi, tujuan
        $kategori = Kategori::where('kode_kategori', 'GV.RR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.RR-001',
                'judul'           => 'Pimpinan organisasi menetapkan keamanan siber sebagai prioritas di organisasi dalam bentuk kebijakan atau komitmen pimpinan yang sesuai dengan kondisi bisnis/layanan dan operasional organisasi.',
                'deskripsi'       => 'Menetapkan dan mengomunikasikan prioritas untuk misi, tujuan, dan kegiatan pelindungan IIV di organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.RR-002',
                'judul'           => 'Penyelenggara Sistem Elektronik mengomunikasikan perihal komitmen keamanan siber di organisasinya dengan pihak-pihak yang terkait dengan bisnis/layanan organisasi (termasuk kepada penyedia pihak ketiga).',
                'deskripsi'       => 'Menetapkan dan mengomunikasikan prioritas untuk misi, tujuan, dan kegiatan pelindungan IIV di organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.1.2 — Mengidentifikasi ketergantungan organisasi dengan pihak terk
        $kategori = Kategori::where('kode_kategori', 'GV.RR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.RR-003',
                'judul'           => 'Penyelenggara Sistem Elektronik mengidentifikasi unit kerja di internal organisasinya, maupun pihak lain di luar organisasinya yang memiliki ketergantungan, baik secara langsung maupun tidak langsung terhadap operasional layanan Sistem Elektronik di organisasinya.',
                'deskripsi'       => 'Mengidentifikasi ketergantungan organisasi dengan pihak terkait lainnya',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.1.3 — Mengidentifikasi dan mengomunikasikan peran organisasi di da
        $kategori = Kategori::where('kode_kategori', 'GV.RR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.RR-004',
                'judul'           => 'Penyelenggara Sistem Elektronik mengidentifikasi dan menetapkan unit kerja atau fungsi yang memiliki tugas dan tanggung jawab dalam menerapkan Keamanan Siber di organisasinya.',
                'deskripsi'       => 'Mengidentifikasi dan mengomunikasikan peran organisasi di dalam sektor Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.RR-005',
                'judul'           => 'Penyelenggara Sistem Elektronik mengidentifikasi peran, aktivitas, proses, dan narahubung dari pemangku kepentingan yang mendukung ekosistem bisnis atau layanan Sistem Elektronik, baik di dalam atau di luar organisasi.',
                'deskripsi'       => 'Mengidentifikasi dan mengomunikasikan peran organisasi di dalam sektor Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.2.1 — Menetapkan dan mengomunikasikan kebijakan keamanan siber di 
        $kategori = Kategori::where('kode_kategori', 'GV.PO')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.PO-006',
                'judul'           => 'Penyelenggara Sistem Elektronik menyusun, menetapkan, dan mengembangkan kebijakan tentang keamanan siber sesuai dengan standar yang berlaku di sektornya dan/atau peraturan perundang-undangan',
                'deskripsi'       => 'Menetapkan dan mengomunikasikan kebijakan keamanan siber di lingkungan Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.PO-007',
                'judul'           => 'Penyelenggara Sistem Elektronik mengomunikasikan kebijakan kepada seluruh personel yang relevan, serta mengoordinasikan dan menyepakati metode berbagi informasi mengenai kebijakan dan prosedur yang ada di organisasi dengan para pemangku kepentingan eksternal.',
                'deskripsi'       => 'Menetapkan dan mengomunikasikan kebijakan keamanan siber di lingkungan Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.PO-008',
                'judul'           => 'Penyelenggara Sistem Elektronik meninjau dan merevisi kebijakannya secara berkelanjutan sesuai dengan setiap perubahan dalam peraturan perundang-undangan yang relevan, standar dan/atau pedoman industri yang berlaku di sektornya.',
                'deskripsi'       => 'Menetapkan dan mengomunikasikan kebijakan keamanan siber di lingkungan Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.2.2 — Mengembangkan strategi untuk meningkatkan pelindungan terhad
        $kategori = Kategori::where('kode_kategori', 'GV.PO')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.PO-009',
                'judul'           => 'Penyelenggara Sistem Elektronik senantiasa mengembangkan strategi dalam melindungi aset informasi dengan mempertimbangkan manajemen risiko yang berlaku di organisasi.',
                'deskripsi'       => 'Mengembangkan strategi untuk meningkatkan pelindungan terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.PO-010',
                'judul'           => 'Penyelenggara Sistem Elektronik harus menetapkan sasaran atau target penerapan keamanan siber pada fungsi dan tingkatan yang relevan.',
                'deskripsi'       => 'Mengembangkan strategi untuk meningkatkan pelindungan terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.2.3 — Menetapkan persyaratan yang dibutuhkan untuk mendukung opera
        $kategori = Kategori::where('kode_kategori', 'GV.PO')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.PO-011',
                'judul'           => 'Penyelenggara Sistem Elektronik menyusun kebijakan standar operasional prosedur terhadap setiap layanan yang mendukung Sistem Elektronik baik dalam kondisi normal, jika terjadi insiden siber, dan pasca insiden siber.',
                'deskripsi'       => 'Menetapkan persyaratan yang dibutuhkan untuk mendukung operasional Sistem Elektronik pada semua keadaan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.2.4 — Menetapkan kebijakan penggunaan aset informasi bagi pegawai 
        $kategori = Kategori::where('kode_kategori', 'GV.PO')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.PO-012',
                'judul'           => 'Penyelenggara Sistem Elektronik menyusun kebijakan yang diperlukan untuk menjaga ketersediaan aset informasi, seperti kebijakan penggunaan perangkat pribadi di kantor (bring your own devices), kebijakan instalasi perangkat lunak pada perangkat kantor, kebijakan klasifikasi informasi, dsb.',
                'deskripsi'       => 'Menetapkan kebijakan penggunaan aset informasi bagi pegawai dan pihak ketiga',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.3.1 — Mengelola daftar inventaris aset informasi
        $kategori = Kategori::where('kode_kategori', 'ID.AM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-013',
                'judul'           => 'Dokumentasikan dan kelola dengan tepat daftar inventaris aset informasi seperti perangkat keras, perangkat lunak, data, dan layanan TIK yang akan dilindungi,  beserta informasi manajemennya (misalnya nama aset, versi, alamat jaringan, nama penanggungjawab, informasi lisensi, dsb).',
                'deskripsi'       => 'Mengelola daftar inventaris aset informasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-014',
                'judul'           => 'Penyelenggara Sistem Elektronik memastikan pemberian label pada perangkat aset informasi oleh pihak yang berwenang di organisasi',
                'deskripsi'       => 'Mengelola daftar inventaris aset informasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.3.2 — Memetakan jalur komunikasi dan alur data pada organisasi
        $kategori = Kategori::where('kode_kategori', 'ID.AM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-015',
                'judul'           => 'Penyelenggara Sistem Elektronik menyusun dokumentasi dan mengelola diagram jalur komunikasi jaringan dan aliran data dengan tepat dalam organisasi.',
                'deskripsi'       => 'Memetakan jalur komunikasi dan alur data pada organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.3.3 — Menyusun katalog sistem informasi eksternal yang menggunakan
        $kategori = Kategori::where('kode_kategori', 'ID.AM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-016',
                'judul'           => 'Dokumentasikan dan kelola dengan tepat daftar sistem informasi eksternal yang menggunakan data atau layanan Sistem Elektronik',
                'deskripsi'       => 'Menyusun katalog sistem informasi eksternal yang menggunakan data milik organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-017',
                'judul'           => 'Dokumentasikan dan kelola dengan tepat daftar sistem informasi eksternal yang digunakan oleh Penyelenggara Sistem Elektronik.',
                'deskripsi'       => 'Menyusun katalog sistem informasi eksternal yang menggunakan data milik organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.3.4 — Menyusun prioritas aset informasi berdasarkan klasifikasi, k
        $kategori = Kategori::where('kode_kategori', 'ID.AM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-018',
                'judul'           => 'Mengklasifikasikan dan memprioritaskan aset informasi seperti, perangkat keras, perangkat lunak, data, dan layanan TIK lainnya berdasarkan fungsi, kekritisan, dan nilai bisnis',
                'deskripsi'       => 'Menyusun prioritas aset informasi berdasarkan klasifikasi, kekritisan, dan nilai bisnisnya',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.3.5 — Mengendalikan aset informasi milik organisasi
        $kategori = Kategori::where('kode_kategori', 'ID.AM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-019',
                'judul'           => 'Menentukan metode untuk memastikan ketertelusuran aset informasi seperti membuat catatan mengenai tanggal produksi atau pengadaan aset, kondisi aset, catatan pemakaian, dan pelaporan kepada unit kerja terkait.',
                'deskripsi'       => 'Mengendalikan aset informasi milik organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.AM-020',
                'judul'           => 'Secara aktif memeriksa keterbaharuan dan memperbaharui dari setiap versi perangkat lunak dan perangkat keras yang digunakan oleh organisasi.',
                'deskripsi'       => 'Mengendalikan aset informasi milik organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.1 — Mengidentifikasi dan mendokumentasikan kerentanan terhadap a
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-021',
                'judul'           => 'Identifikasi kerentanan terhadap seluruh aset informasi di organisasi, misalnya melalui penetration testing dan vulnerability assessment, serta dokumentasikan daftar kerentanan yang teridentifikasi tersebut bersama dengan daftar aset terkait.',
                'deskripsi'       => 'Mengidentifikasi dan mendokumentasikan kerentanan terhadap aset informasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.2 — Mengidentifikasi dan mendokumentasikan informasi terkait anc
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-022',
                'judul'           => 'Penyelenggara Sistem Elektronik mengumpulkan informasi termasuk kerentanan dan ancaman dari sumber internal dan eksternal (melalui pengujian internal, informasi dari pihak berwajib, hasil penelitian keamanan, dll.)',
                'deskripsi'       => 'Mengidentifikasi dan mendokumentasikan informasi terkait ancaman dan kerentanan yang diperoleh dari internal maupun eksternal',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-023',
                'judul'           => 'Menganalisis informasi tersebut apakah termasuk kedalam konteks risiko terhadap aset informasi, dan mendokumentasikannya.',
                'deskripsi'       => 'Mengidentifikasi dan mendokumentasikan informasi terkait ancaman dan kerentanan yang diperoleh dari internal maupun eksternal',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.3 — Mengidentifikasi potensi dampak terhadap layanan Sistem Elek
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-024',
                'judul'           => 'Penyelenggara Sistem Elektronik perlu memeriksa pada setiap fungsi penting organisasi apakah ada risiko keamanan yang diketahui termasuk kedalam kategori membahayakan keselamatan, menimbulkan kerugian, dan mengancam keamanan negara.',
                'deskripsi'       => 'Mengidentifikasi potensi dampak terhadap layanan Sistem Elektronik dan kemungkinan terjadinya dampak tersebut',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.4 — Menganalisis nilai risiko terhadap Sistem Elektronik
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-025',
                'judul'           => 'Pertimbangkan ancaman, kerentanan, kemungkinan, dan dampak saat menganalisis risiko',
                'deskripsi'       => 'Menganalisis nilai risiko terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-026',
                'judul'           => 'Penyelenggara Sistem Elektronik menentukan level risiko dan prioritas mitigasinya',
                'deskripsi'       => 'Menganalisis nilai risiko terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-027',
                'judul'           => 'Penyelenggara Sistem Elektronik  memiliki kriteria yang jelas dan konsisten untuk menentukan tingkat risiko siber dan toleransi risiko untuk setiap aset informasi vital',
                'deskripsi'       => 'Menganalisis nilai risiko terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-028',
                'judul'           => 'Penyelenggara Sistem Elektronik melakukan penilaian risiko siber secara berkala dan menyeluruh, dengan melibatkan semua pemangku kepentingan yang relevan.',
                'deskripsi'       => 'Menganalisis nilai risiko terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-029',
                'judul'           => 'Penyelenggara Sistem Elektronik menentukan tingkat risiko terkait keamanan siber yang dapat diterima',
                'deskripsi'       => 'Menganalisis nilai risiko terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-030',
                'judul'           => 'Penyelenggara Sistem Elektronik memiliki proses untuk menganalisis dan mengevaluasi data dan informasi yang terkait dengan risiko siber, termasuk penyebab, dampak, dan peluang',
                'deskripsi'       => 'Menganalisis nilai risiko terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-031',
                'judul'           => 'Penyelenggara Sistem Elektronik memiliki kebijakan dan prosedur yang efektif untuk mengurangi, menghindari, mentransfer, atau menerima risiko siber sesuai dengan tingkat risiko dan toleransi risiko yang ditetapkan',
                'deskripsi'       => 'Menganalisis nilai risiko terhadap Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.5 — Mengidentifikasi dan menyusun prioritas mitigasi terhadap ri
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-032',
                'judul'           => 'Berdasarkan hasil penilaian risiko, tentukan dengan jelas rincian tindakan untuk mencegah kemungkinan risiko keamanan, dan dokumentasikan hasil yang terorganisir dari ruang lingkup dan prioritas tindakan.',
                'deskripsi'       => 'Mengidentifikasi dan menyusun prioritas mitigasi terhadap risiko',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-033',
                'judul'           => 'Evaluasi hasil penerapan respon risiko secara berkelanjutan.',
                'deskripsi'       => 'Mengidentifikasi dan menyusun prioritas mitigasi terhadap risiko',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.6 — Menentukan dan mengomunikasikan toleransi risiko organisasi
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-034',
                'judul'           => 'Menentukan tingkat toleransi risiko organisasi berdasarkan hasil penilaian risiko dan kebijakan yang berlaku.',
                'deskripsi'       => 'Menentukan dan mengomunikasikan toleransi risiko organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-035',
                'judul'           => 'Penyelenggara Sistem Elektronik memiliki mekanisme untuk mengkomunikasikan hasil dan rekomendasi dari proses manajemen risiko kepada pihak-pihak yang berwenang dan relevan',
                'deskripsi'       => 'Menentukan dan mengomunikasikan toleransi risiko organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.7 — Mengelola hasil penerapan manajemen risiko yang telah diteta
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-036',
                'judul'           => 'Konfirmasi status implementasi manajemen risiko keamanan siber organisasi dan komunikasikan hasilnya kepada pihak yang tepat di dalam organisasi (misalnya pimpinan organisasi).',
                'deskripsi'       => 'Mengelola hasil penerapan manajemen risiko yang telah ditetapkan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-037',
                'judul'           => 'tetapkan serta terapkan proses untuk mengonfirmasi status penerapan manajemen risiko keamanan pihak terkait.',
                'deskripsi'       => 'Mengelola hasil penerapan manajemen risiko yang telah ditetapkan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-038',
                'judul'           => 'Penyelenggara Sistem Elektronik memiliki meknisme untuk melakukan audit internal dan eksternal terhadap proses manajemen risiko dan mengimplementasikan rekomendasi perbaikan',
                'deskripsi'       => 'Mengelola hasil penerapan manajemen risiko yang telah ditetapkan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.4.8 — Melakukan reviu terhadap hasil penerapan manajemen risiko
        $kategori = Kategori::where('kode_kategori', 'ID.RA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'ID.RA-039',
                'judul'           => 'Penyelenggara Sistem Elektronik melakukan reviu terhadap manajemen risiko secara periodik, atau apabila menemukan data atau informasi baru yang berpotensi menambah atau mengubah profil risiko.',
                'deskripsi'       => 'Melakukan reviu terhadap hasil penerapan manajemen risiko',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.5.1 — Mengidentifikasi dan menetapkan proses manajemen risiko rant
        $kategori = Kategori::where('kode_kategori', 'GV.SC')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-040',
                'judul'           => 'Merumuskan standar tindakan keamanan yang relevan dengan rantai pasokan dan menyepakati konten dengan mitra bisnis setelah memperjelas ruang lingkup tanggung jawab masing-masing',
                'deskripsi'       => 'Mengidentifikasi dan menetapkan proses manajemen risiko rantai pasok',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-041',
                'judul'           => 'Hal yang perlu dipertimbangkan dalam proses manajemen risiko rantai pasokan diantaranya adalah penentuan jenis akses yang diberikan, alasan kebutuhan akses, metode akses, jangka waktu, dan potensi risiko yang terjadi apabila akses tersebut disalahgunakan',
                'deskripsi'       => 'Mengidentifikasi dan menetapkan proses manajemen risiko rantai pasok',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.5.2 — Mengidentifikasi pemasok dan mitra pihak ketiga dari setiap 
        $kategori = Kategori::where('kode_kategori', 'GV.SC')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-042',
                'judul'           => 'identifikasi peran dan tanggung jawab keamanan siber di pemangku kepentingan pihak ketiga (misalnya, pemasok, pelanggan, atau mitra), dan pihak lainnya yang berhubungan dengan Penyelenggara Sistem Elektronik.',
                'deskripsi'       => 'Mengidentifikasi pemasok dan mitra pihak ketiga dari setiap aset informasi di Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-043',
                'judul'           => 'Merumuskan dan mengelola persyaratan keamanan yang berlaku untuk anggota/personel pihak ketiga, dan juga pemangku kepentingan lainnya yang terlibat dalam layanan yang disediakan oleh pihak ketiga.',
                'deskripsi'       => 'Mengidentifikasi pemasok dan mitra pihak ketiga dari setiap aset informasi di Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.5.3 — Memastikan poin-poin perjanjian kerja sama yang digunakan un
        $kategori = Kategori::where('kode_kategori', 'GV.SC')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-044',
                'judul'           => 'Saat menandatangani kontrak dengan pihak ketiga, periksa apakah manajemen pihak ketiga telah dengan benar mematuhi persyaratan keamanan, standar, dan peraturan perundangan yang berlaku, dengan mempertimbangkan tujuan kontrak tersebut dan hasil manajemen risiko.',
                'deskripsi'       => 'Memastikan poin-poin perjanjian kerja sama yang digunakan untuk pemasok dan mitra pihak ketiga telah sesuai dengan kebijakan keamanan siber pada Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-045',
                'judul'           => 'Saat menandatangani kontrak dengan pihak ketiga, periksa apakah produk dan layanan yang disediakan oleh pihak ketiga sesuai dengan persyaratan keamanan yang ada di organisasi.',
                'deskripsi'       => 'Memastikan poin-poin perjanjian kerja sama yang digunakan untuk pemasok dan mitra pihak ketiga telah sesuai dengan kebijakan keamanan siber pada Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.5.4 — Melakukan pemeriksaan secara periodik terhadap pemasok dan m
        $kategori = Kategori::where('kode_kategori', 'GV.SC')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-046',
                'judul'           => 'Melakukan penilaian secara berkala melalui audit, hasil pengujian, atau pemeriksaan dari pihak terkait untuk memastikan pihak ketiga memenuhi kewajiban kontraktual mereka.',
                'deskripsi'       => 'Melakukan pemeriksaan secara periodik terhadap pemasok dan mitra pihak ketiga terkait pemenuhan kewajiban kerja sama dan keamanannya',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-047',
                'judul'           => 'Merumuskan dan menerapkan prosedur untuk mengatasi ketidakpatuhan terhadap persyaratan kontrak yang ditemukan.',
                'deskripsi'       => 'Melakukan pemeriksaan secara periodik terhadap pemasok dan mitra pihak ketiga terkait pemenuhan kewajiban kerja sama dan keamanannya',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-048',
                'judul'           => 'Mengumpulkan dan menyimpan data dengan aman yang membuktikan bahwa organisasi memenuhi kewajiban kontraktualnya dengan pihak atau individu lain yang relevan, dan mempersiapkannya untuk pengungkapan jika diperlukan dalam rangka penegakan hukum.',
                'deskripsi'       => 'Melakukan pemeriksaan secara periodik terhadap pemasok dan mitra pihak ketiga terkait pemenuhan kewajiban kerja sama dan keamanannya',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 1.5.5 — Menyiapkan rencana penanggulangan dan pemulihan pada layanan
        $kategori = Kategori::where('kode_kategori', 'GV.SC')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-049',
                'judul'           => 'Menyiapkan dan menguji prosedur respons insiden dengan pihak terkait yang terlibat dalam aktivitas respons insiden untuk memastikan tindakan respons dilaksanakan dalam rantai pasokan.',
                'deskripsi'       => 'Menyiapkan rencana penanggulangan dan pemulihan pada layanan Sistem Elektronik dengan pihak ketiga yang mendukung layanan tersebut',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-050',
                'judul'           => 'menyusun prosedur keamanan yang akan dijalankan ketika kontrak dengan pihak ketiga selesai. (misalnya, pemutusan hak akses ketika berakhirnya masa kontrak)',
                'deskripsi'       => 'Menyiapkan rencana penanggulangan dan pemulihan pada layanan Sistem Elektronik dengan pihak ketiga yang mendukung layanan tersebut',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'GV.SC-051',
                'judul'           => 'senantiasa meningkatkan standar langkah-langkah keamanan yang relevan dengan mitra rantai pasok.',
                'deskripsi'       => 'Menyiapkan rencana penanggulangan dan pemulihan pada layanan Sistem Elektronik dengan pihak ketiga yang mendukung layanan tersebut',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.1.1 — Mengelola identitas dan kredensial yang menggunakan layanan 
        $kategori = Kategori::where('kode_kategori', 'PR.AA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AA-052',
                'judul'           => 'Menetapkan dan menerapkan prosedur untuk menerbitkan, mengelola, memeriksa, membatalkan, dan memantau informasi tentang identitas dan kredensial terhadap aset informasi dan personel yang menggunakan Sistem Elektronik.',
                'deskripsi'       => 'Mengelola identitas dan kredensial yang menggunakan layanan IIV',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.1.2 — Mengelola akses jarak jauh terhadap layanan Sistem Elektroni
        $kategori = Kategori::where('kode_kategori', 'PR.AA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AA-053',
                'judul'           => 'Penyelenggara Sistem Elektronik menyusun prosedur tentang mekanisme identifikasi pengguna layanan, pemberian akses, dan otorisasi terhadap layanan, termasuk pemberian koneksi terhadap pengguna, perangkat IoT, dan/atau server.',
                'deskripsi'       => 'Mengelola akses jarak jauh terhadap layanan Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AA-054',
                'judul'           => 'Tersedianya dan diterapkannya prosedur pencegahan terhadap upaya memasuki perangkat atau jaringan secara tidak sah, dengan menerapkan langkah-langkah seperti menerapkan fungsi untuk penguncian setelah sejumlah upaya masuk yang gagal dan memberikan interval waktu hingga keamanannya dipastikan.',
                'deskripsi'       => 'Mengelola akses jarak jauh terhadap layanan Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.1.3 — Mengelola izin akses dan otorisasi layanan Sistem Elektronik
        $kategori = Kategori::where('kode_kategori', 'PR.AA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AA-055',
                'judul'           => 'Menyusun dan menerapkan prosedur untuk memisahkan hak akses sesuai tugas dan area tanggung jawab (misalnya, pisahkan fungsi untuk pengguna dari fungsi untuk administrator sistem)',
                'deskripsi'       => 'Mengelola izin akses dan otorisasi layanan Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AA-056',
                'judul'           => 'Tersedianya prosedur pembatasan komunikasi oleh perangkat dan server kepada pengguna sesuai dengan tingkat risikonya.',
                'deskripsi'       => 'Mengelola izin akses dan otorisasi layanan Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.1.4 — Memastikan penerapan sistem autentikasi terhadap pengguna, p
        $kategori = Kategori::where('kode_kategori', 'PR.AA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AA-057',
                'judul'           => 'Perangkat, pengguna, dan aset informasi lainnya menggunakan sistem otentikasi tertentu (misalnya, multi-factor authentication) sesuai dengan tingkat risiko nya terhadap sistem.',
                'deskripsi'       => 'Memastikan penerapan sistem autentikasi terhadap pengguna, perangkat, dan aset informasi sesuai tingkat risikonya',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.2.1 — Menyediakan prosedur operasional pelindungan terhadap aset f
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-058',
                'judul'           => 'Menetapkan dan menerapkan prosedur keamanan fisik terhadap akses kontrol yang sesuai seperti mengunci dan membatasi akses ke area tempat perangkat dan server dipasang, menggunakan kontrol masuk dan keluar, otentikasi biometrik, memasang kamera pengintai, dan/atau memeriksa barang bawaan.',
                'deskripsi'       => 'Menyediakan prosedur operasional pelindungan terhadap aset fisik yang mendukung layanan Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-059',
                'judul'           => 'Tersedianya dan diterapkannya prosedur pelindungan fisik seperti menyiapkan catu daya cadangan, fasilitas proteksi kebakaran, dan perlindungan dari resapan air yang mengikuti kebijakan dan standar yang berlaku.',
                'deskripsi'       => 'Menyediakan prosedur operasional pelindungan terhadap aset fisik yang mendukung layanan Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-060',
                'judul'           => 'Tersedianya prosedur dan sarana pengamanan terhadap perangkat komputer yang digunakan untuk pengolahan data Sistem Elektronik.',
                'deskripsi'       => 'Menyediakan prosedur operasional pelindungan terhadap aset fisik yang mendukung layanan Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.2.2 — Memastikan proses perbaikan dan pemeliharaan aset informasi 
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-061',
                'judul'           => 'Tentukan metode untuk melakukan pembaruan keamanan dan sejenisnya pada perangkat dan server. Kemudian, terapkan pembaruan keamanan tersebut dengan teknologi yang benar dan tepat pada waktunya.',
                'deskripsi'       => 'Memastikan proses perbaikan dan pemeliharaan aset informasi pada layanan Sistem Elektronik dilakukan, dicatat, dan dikendalikan sesuai prosedur',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-062',
                'judul'           => 'Dokumentasikan kegiatan pembaruan keamanan pada perangkat organisasi dan laporkan kepada manajemen secara berkala.',
                'deskripsi'       => 'Memastikan proses perbaikan dan pemeliharaan aset informasi pada layanan Sistem Elektronik dilakukan, dicatat, dan dikendalikan sesuai prosedur',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.2.3 — Memastikan proses pemeliharaan jarak jauh terhadap aset info
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-063',
                'judul'           => 'Mengidentifikasi perangkat yang memiliki mekanisme pembaruan jarak jauh untuk melakukan pembaruan massal berbagai program perangkat lunak (OS, driver, dan aplikasi) melalui perintah jarak jauh.',
                'deskripsi'       => 'Memastikan proses pemeliharaan jarak jauh terhadap aset informasi pada layanan Sistem Elektronik dilakukan dengan persetujuan penanggung jawab layanan Sistem Elektronik dan didokumentasikan sesuai prosedur',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-064',
                'judul'           => 'Melakukan pemeliharaan perangkat dan server yang telah disetujui dari jarak jauh dan mencatat setiap log masuknya, sehingga akses yang tidak sah dapat dicegah.',
                'deskripsi'       => 'Memastikan proses pemeliharaan jarak jauh terhadap aset informasi pada layanan Sistem Elektronik dilakukan dengan persetujuan penanggung jawab layanan Sistem Elektronik dan didokumentasikan sesuai prosedur',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.2.4 — Memastikan lingkungan fisik aset informasi pada layanan Sist
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-065',
                'judul'           => 'Penyelenggara Sistem Elektronik memastikan lingkungan fisik aset Sistem Elektronik dipantau secara tepat melalui pengaturan, perekaman, dan pemantauan akses fisik terhadap aset Sistem Elektronik. (misalnya cctv, akses kontrol, sensor, dll).',
                'deskripsi'       => 'Memastikan lingkungan fisik aset informasi pada layanan Sistem Elektronik dipantau secara berkala untuk mendeteksi potensi ancaman',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.2.5 — Memastikan prosedur dan penerapannya senantiasa ditinjau dan
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-066',
                'judul'           => 'Penyelenggara Sistem Elektronik senantiasa melakukan peninjauan, analisis, dan peningkatan terhadap kontrol keamanan yang diterapkan sesuai hasil reviu dari respons insiden keamanan dan hasil pemantauan, pengukuran, dan evaluasi ancaman internal dan eksternal.',
                'deskripsi'       => 'Memastikan prosedur dan penerapannya senantiasa ditinjau dan ditingkatkan sesuai perkembangan ancaman',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.3.1 — Pelindungan terhadap data yang tersimpan pada Penyelenggara 
        $kategori = Kategori::where('kode_kategori', 'PR.DS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-067',
                'judul'           => 'Jika Penyelenggara Sistem Elektronik bertukar informasi yang perlu dilindungi dengan organisasi lain, maka Penyelenggara Sistem Elektronik perlu meminta organisasi lain tersebut untuk menyetujui persyaratan keamanan untuk pelindungan informasi tersebut.',
                'deskripsi'       => 'Pelindungan terhadap data yang tersimpan pada Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-068',
                'judul'           => 'Mengenkripsi informasi dengan tingkat kekuatan keamanan yang sesuai standar keamanan.',
                'deskripsi'       => 'Pelindungan terhadap data yang tersimpan pada Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.3.2 — Pelindungan terhadap data yang terkirim dari Penyelenggara S
        $kategori = Kategori::where('kode_kategori', 'PR.DS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-069',
                'judul'           => 'Penyelenggara Sistem Elektronik memastikan saluran komunikasi menerapkan enkripsi saat berkomunikasi antara perangkat Sistem Elektronik dan server Sistem Elektronik.',
                'deskripsi'       => 'Pelindungan terhadap data yang terkirim dari Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-070',
                'judul'           => 'Penyelenggara Sistem Elektronik memastikan kunci enkripsi dikontrol dengan aman sepanjang siklus hidup kunci enkripsi tersebut untuk memastikan pengoperasian yang benar dan data yang ditransmisikan, diterima, dan disimpan dengan aman.',
                'deskripsi'       => 'Pelindungan terhadap data yang terkirim dari Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.3.3 — Memastikan ketersediaan kapasitas ruang penyimpanan data yan
        $kategori = Kategori::where('kode_kategori', 'PR.DS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-071',
                'judul'           => 'Penyelenggara Sistem Elektronik menyediakan sumber daya yang cukup untuk setiap sistem Sistem Elektronik (misalnya ruang penyimpanan, sumber daya, dan sistem redundan),',
                'deskripsi'       => 'Memastikan ketersediaan kapasitas ruang penyimpanan data yang memadai',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-072',
                'judul'           => 'Penyelenggara Sistem Elektronik memastikan dilakukannya pemeriksaan kualitas ruang penyimpanan data secara berkala, pendeteksi kegagalan operasional, dan pembaharuan perangkat lunak untuk perangkat penyimpanan data.',
                'deskripsi'       => 'Memastikan ketersediaan kapasitas ruang penyimpanan data yang memadai',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.3.4 — Mengimplementasikan pelindungan dari kebocoran data
        $kategori = Kategori::where('kode_kategori', 'PR.DS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-073',
                'judul'           => 'Saat menangani informasi yang akan dilindungi atau pengadaan perangkat yang memiliki fungsi penting bagi organisasi, pilih perangkat dan server yang dilengkapi dengan perangkat anti-tampering.',
                'deskripsi'       => 'Mengimplementasikan pelindungan dari kebocoran data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-074',
                'judul'           => 'Memastikan bahwa jalur komunikasi yang digunakan untuk mengirim informasi telah dilindungi dengan kontrol keamanan yang tepat.',
                'deskripsi'       => 'Mengimplementasikan pelindungan dari kebocoran data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.3.5 — Mengimplementasikan mekanisme pengecekan integritas data unt
        $kategori = Kategori::where('kode_kategori', 'PR.DS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-075',
                'judul'           => 'Melakukan pemeriksaan integritas perangkat lunak yang berjalan di perangkat dan server pada waktu yang ditentukan oleh organisasi, untuk mencegah pemasangan perangkat lunak yang tidak sah.',
                'deskripsi'       => 'Mengimplementasikan mekanisme pengecekan integritas data untuk verifikasi perangkat lunak, perangkat keras, dan data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-076',
                'judul'           => 'Melakukan pemeriksaan integritas informasi yang akan dikirim, diterima, dan disimpan.',
                'deskripsi'       => 'Mengimplementasikan mekanisme pengecekan integritas data untuk verifikasi perangkat lunak, perangkat keras, dan data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-077',
                'judul'           => 'Memperkenalkan mekanisme pemeriksaan integritas untuk memverifikasi integritas perangkat keras.',
                'deskripsi'       => 'Mengimplementasikan mekanisme pengecekan integritas data untuk verifikasi perangkat lunak, perangkat keras, dan data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-078',
                'judul'           => 'Konfirmasikan bahwa perangkat keras dan perangkat lunak adalah produk asli dan memiliki sertifikat keamanan.',
                'deskripsi'       => 'Mengimplementasikan mekanisme pengecekan integritas data untuk verifikasi perangkat lunak, perangkat keras, dan data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-079',
                'judul'           => 'Pelihara, perbarui, dan kelola informasi seperti asal data, dan riwayat pemrosesan data, di seluruh siklus hidup data.',
                'deskripsi'       => 'Mengimplementasikan mekanisme pengecekan integritas data untuk verifikasi perangkat lunak, perangkat keras, dan data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.3.6 — Memastikan prosedur pencadangan data dilakukan, dipelihara, 
        $kategori = Kategori::where('kode_kategori', 'PR.DS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-080',
                'judul'           => 'Tersedianya dan diterapkannya prosedur pencadangan sistem secara berkala dan pengujian kehandalan komponen untuk memastikan ketersediaan sistem.',
                'deskripsi'       => 'Memastikan prosedur pencadangan data dilakukan, dipelihara, dan diuji secara berkala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.3.7 — Menyediakan kebijakan pemusnahan data
        $kategori = Kategori::where('kode_kategori', 'PR.DS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.DS-081',
                'judul'           => 'Saat akan menghapuskan perangkat dan aset informasi, prosedur penghapusan data yang disimpan dari perangkat dan server serta informasi penting lainya (misalnya, kunci pribadi dan sertifikat digital), atau dibuat agar tidak dapat dibaca.',
                'deskripsi'       => 'Menyediakan kebijakan pemusnahan data',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.4.1 — Menyediakan prosedur konfigurasi dasar sistem dan kendali pe
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-082',
                'judul'           => 'Tersedianya dan diterapkannya prosedur untuk keamanan pada saat pengaturan sistem (misalnya, prosedur penerapan kata sandi, prosedur penerapan izin akses, dsb)',
                'deskripsi'       => 'Menyediakan prosedur konfigurasi dasar sistem dan kendali perubahan konfigurasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-083',
                'judul'           => 'tersedianya dan diterapkanya prosedur untuk melakukan perubahan pengaturan pada perangkat.',
                'deskripsi'       => 'Menyediakan prosedur konfigurasi dasar sistem dan kendali perubahan konfigurasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-084',
                'judul'           => 'Tersedianya dan diterapkannya prosedur Pembatasan perangkat lunak yang akan ditambahkan pada perangkat dan server.',
                'deskripsi'       => 'Menyediakan prosedur konfigurasi dasar sistem dan kendali perubahan konfigurasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.4.2 — Mengembangkan dan mengimplementasikan rencana manajemen kere
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-085',
                'judul'           => 'Penyelenggara Sistem Elektronik mengembangkan dan mengimplementasikan rencana manajemen kerentanan yang meliputi mekanisme pengumpulan informasi kerentanan, inventarisasi kerentanan, hingga perbaikan terhadap kerentanan yang ditemukan pada aplikasi.',
                'deskripsi'       => 'Mengembangkan dan mengimplementasikan rencana manajemen kerentanan.',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.4.3 — Memastikan bahwa lingkungan pengembangan dan pengujian siste
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-086',
                'judul'           => 'Penggunaan lingkungan pengembangan sistem yang berbeda dari lingkungan produksi yang meliputi pemisahan terhadap media penyimpanan, jaringan, lingkungan kerja, dsb.',
                'deskripsi'       => 'Memastikan bahwa lingkungan pengembangan dan pengujian sistem dibedakan dari lingkungan produksi atau operasional',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.4.4 — Mengimplementasikan prosedur pengembangan sistem yang aman
        $kategori = Kategori::where('kode_kategori', 'PR.PS')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.PS-087',
                'judul'           => 'Tersedianya dan diterapkannya prosedur pengembangan perangkat lunak yang selalu memperhatikan aspek keamanan pada setiap tahapan siklus hidup pengembangannya.',
                'deskripsi'       => 'Mengimplementasikan prosedur pengembangan sistem yang aman',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.5.1 — Menerapkan sistem yang dikonfigurasi dengan prinsip fungsion
        $kategori = Kategori::where('kode_kategori', 'PR.IR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-088',
                'judul'           => 'Minimalkan fungsi perangkat dan server dengan memblokir secara fisik dan logis port jaringan, USB, dan port serial yang tidak perlu, yang mengakses secara langsung bagian utama perangkat dan server.',
                'deskripsi'       => 'Menerapkan sistem yang dikonfigurasi dengan prinsip fungsionalitas minimum',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-089',
                'judul'           => 'Memastikan Removable Media terlindungi dan penggunaannya terbatas sesuai dengan kebijakan.',
                'deskripsi'       => 'Menerapkan sistem yang dikonfigurasi dengan prinsip fungsionalitas minimum',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.5.2 — Menerapkan pelindungan terhadap jaringan komunikasi, akses s
        $kategori = Kategori::where('kode_kategori', 'PR.IR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-090',
                'judul'           => 'Organisasi menentukan dan menerapkan segmentasi dan/atau pembagian zonasi jaringan komunikasi/internet. (misalnya dibagi menjadi lingkungan pengembangan, pengujian, lingkungan produksi, dan lingkungan lain dalam organisasi)',
                'deskripsi'       => 'Menerapkan pelindungan terhadap jaringan komunikasi, akses sistem informasi, dan akses sistem kendali',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-091',
                'judul'           => 'Organisasi juga melakukan isolasi terhadap jaringan yang menghubungkan dengan perangkat penting, misalnya perangkat SCADA atau ICS (Industrial Control System).',
                'deskripsi'       => 'Menerapkan pelindungan terhadap jaringan komunikasi, akses sistem informasi, dan akses sistem kendali',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.5.3 — Menggunakan perangkat-perangkat jaringan yang menerapkan fun
        $kategori = Kategori::where('kode_kategori', 'PR.IR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-092',
                'judul'           => 'Memastikan perangkat jaringan mampu menerapkan mekanisme (misalnya, fail safe, load balancer, atau hot swap) yang perlu diimplementasikan untuk mencapai persyaratan ketahanan dalam situasi normal dan situasi yang merugikan.',
                'deskripsi'       => 'Menggunakan perangkat-perangkat jaringan yang menerapkan fungsi keamanan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-093',
                'judul'           => 'Memastikan fungsi keamanan pada perangkat jaringan dapat digunakan sesuai dengan kebutuhan dan sertifikat keamanan.',
                'deskripsi'       => 'Menggunakan perangkat-perangkat jaringan yang menerapkan fungsi keamanan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.5.4 — Memastikan integritas jaringan senantiasa dilindungi
        $kategori = Kategori::where('kode_kategori', 'PR.IR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-094',
                'judul'           => 'tersedianya prosedur pelindungan terhadap integritas jaringan dengan cara melakukan pengujian dan monitoring terhadap konfigurasi jaringan, seperti pengujian terhadap segmentasi jaringan yang sesuai.',
                'deskripsi'       => 'Memastikan integritas jaringan senantiasa dilindungi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.5.5 — Menerapkan prosedur dan teknologi pencegahan malware
        $kategori = Kategori::where('kode_kategori', 'PR.IR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-095',
                'judul'           => 'Untuk melindungi ketersediaan data terhadap serangan malware, data organisasi harus di-rekam cadang secara berkala.',
                'deskripsi'       => 'Menerapkan prosedur dan teknologi pencegahan malware',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-096',
                'judul'           => 'pemindaian/scanning terhadap removable media yang akan digunakan pada perangkat komputer, notebook, server, atau perangkat pengolah informasi lainnya untuk mencegah masuknya virus dari luar ke dalam perangkat pengolah informasi dan jaringan komunikasi data milik Organisasi.',
                'deskripsi'       => 'Menerapkan prosedur dan teknologi pencegahan malware',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-097',
                'judul'           => 'Mengunduh dan menginstalasi update anti malware terbaru, meliputi antivirus, anti-spyware, spam filtering, web content filtering, dan intrusion detection and prevention system.',
                'deskripsi'       => 'Menerapkan prosedur dan teknologi pencegahan malware',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-098',
                'judul'           => 'Meng-update perangkat komputer dengan melakukan upgrade dan patch sistem operasi dan aplikasi.',
                'deskripsi'       => 'Menerapkan prosedur dan teknologi pencegahan malware',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.5.6 — Memastikan catatan audit atau log aktivitas ditentukan, dido
        $kategori = Kategori::where('kode_kategori', 'PR.IR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-099',
                'judul'           => 'Menentukan dan mendokumentasikan subjek atau ruang lingkup rekaman audit/pencatatan log, dan menerapkan dan meninjau catatan tersebut untuk mendeteksi insiden keamanan berisiko tinggi dengan benar.',
                'deskripsi'       => 'Memastikan catatan audit atau log aktivitas ditentukan, didokumentasikan, diimplementasikan, dan ditinjau sesuai dengan kebijakan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.5.7 — Memastikan bahwa informasi mengenai pelindungan terhadap tek
        $kategori = Kategori::where('kode_kategori', 'PR.IR')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.IR-100',
                'judul'           => 'Berbagi informasi mengenai efektivitas teknologi perlindungan data hanya dengan mitra yang tepat dan terpercaya.',
                'deskripsi'       => 'Memastikan bahwa informasi mengenai pelindungan terhadap teknologi dibagikan hanya kepada pihak tepercaya',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.6.1 — Menerapkan prosedur pengelolaan keamanan terhadap personel
        $kategori = Kategori::where('kode_kategori', 'PR.AT')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-101',
                'judul'           => 'Pemeriksaan verifikasi latar belakang terhadap semua calon personel harus dilakukan sebelum bergabung dengan Penyelenggara Sistem Elektronik dengan mempertimbangkan peraturan dan etika yang berlaku serta proporsional dengan kebutuhan bisnis, dan risiko keamanan.',
                'deskripsi'       => 'Menerapkan prosedur pengelolaan keamanan terhadap personel',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-102',
                'judul'           => 'Perjanjian kontrak kerja harus menyatakan tanggung jawab personel dan organisasi untuk keamanan informasi.',
                'deskripsi'       => 'Menerapkan prosedur pengelolaan keamanan terhadap personel',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-103',
                'judul'           => 'Proses pendisiplinan harus diformalkan dan dikomunikasikan untuk mengambil tindakan terhadap personel dan pihak berkepentingan lainnya yang telah melakukan pelanggaran kebijakan keamanan informasi.',
                'deskripsi'       => 'Menerapkan prosedur pengelolaan keamanan terhadap personel',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-104',
                'judul'           => 'Organisasi menetapkan dan mengomunikasikan kendali terhadap seluruh pegawai dan pihak ketiga setelah penghentian atau penggantian jabatan, tugas dan tanggung jawab keamanan informasi.',
                'deskripsi'       => 'Menerapkan prosedur pengelolaan keamanan terhadap personel',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-105',
                'judul'           => 'Perjanjian kerahasiaan yang mencerminkan kebutuhan organisasi untuk perlindungan informasi harus diidentifikasi, didokumentasikan, ditinjau secara teratur dan ditandatangani oleh personel dan pihak berkepentingan terkait lainnya.',
                'deskripsi'       => 'Menerapkan prosedur pengelolaan keamanan terhadap personel',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-106',
                'judul'           => 'Langkah-langkah keamanan harus diterapkan ketika personel bekerja dari jarak jauh untuk melindungi informasi yang diakses, diproses, atau disimpan di luar lokasi organisasi.',
                'deskripsi'       => 'Menerapkan prosedur pengelolaan keamanan terhadap personel',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-107',
                'judul'           => 'Organisasi harus menyediakan mekanisme bagi personel untuk melaporkan kejadian keamanan informasi yang diamati atau dicurigai melalui saluran yang tepat.',
                'deskripsi'       => 'Menerapkan prosedur pengelolaan keamanan terhadap personel',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.6.2 — Menyelenggarakan pelatihan dan peningkatan kesadaran keamana
        $kategori = Kategori::where('kode_kategori', 'PR.AT')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-108',
                'judul'           => 'Memberikan pelatihan dan pendidikan yang tepat kepada semua individu dalam organisasi dan mengelola catatan sehingga mereka dapat memenuhi peran dan tanggung jawab yang ditugaskan untuk mencegah dan mengatasi terjadinya dan tingkat keparahan insiden keamanan.',
                'deskripsi'       => 'Menyelenggarakan pelatihan dan peningkatan kesadaran keamanan siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-109',
                'judul'           => 'Memberikan pelatihan dan pendidikan keamanan yang sesuai kepada anggota organisasi dan pihak terkait lainnya yang sangat penting dalam manajemen keamanan yang mungkin terlibat dalam pencegahan dan penanggulangan insiden keamanan. Kemudian, kelola catatan pelatihan dan pendidikan keamanan tersebut.',
                'deskripsi'       => 'Menyelenggarakan pelatihan dan peningkatan kesadaran keamanan siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-110',
                'judul'           => 'Meningkatkan isi pelatihan dan pendidikan tentang keamanan kepada anggota organisasi dan pihak terkait lainnya yang sangat penting dalam manajemen keamanan organisasi.',
                'deskripsi'       => 'Menyelenggarakan pelatihan dan peningkatan kesadaran keamanan siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 2.6.3 — Menyusun dan menerapkan kebijakan terkait kompetensi dan kea
        $kategori = Kategori::where('kode_kategori', 'PR.AT')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-111',
                'judul'           => 'Organisasi perlu menetapkan kebijakan terkait Kompetensi yang diperlukan dalam pelaksanaan Keamanan Siber diorganisasinya dengan menacu kepada Peta Okupasi Keamanan Siber Nasional Indonesia.',
                'deskripsi'       => 'Menyusun dan menerapkan kebijakan terkait kompetensi dan keahlian sumber daya manusia keamanan siber yang ada di Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-112',
                'judul'           => 'Organisasi perlu menetapkan kebijakan pengembangan kompetensi SDM keamanan siber melalui pelatihan/pendidikan/workshop.',
                'deskripsi'       => 'Menyusun dan menerapkan kebijakan terkait kompetensi dan keahlian sumber daya manusia keamanan siber yang ada di Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'PR.AT-113',
                'judul'           => 'Organisasi sektor Pemerintah harus meningkatkan keterampilan dan kompetensi teknis dalam keamanan siber serta perilaku personel terhadap keamanan siber secara berkala dan sesuai dengan perkembangan teknologi dan pemanfaatan TIK.',
                'deskripsi'       => 'Menyusun dan menerapkan kebijakan terkait kompetensi dan keahlian sumber daya manusia keamanan siber yang ada di Penyelenggara Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.1.1 — Menetapkan peran dan tanggung jawab organisasi pada kebijaka
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-114',
                'judul'           => 'Memperjelas peran dan tanggung jawab organisasi serta penyedia layanan dalam rangka mendeteksi peristiwa keamanan.',
                'deskripsi'       => 'Menetapkan peran dan tanggung jawab organisasi pada kebijakan pendeteksian Peristiwa Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-115',
                'judul'           => 'Penyelenggara Sistem Elektronik menyiapkan sistem dalam organisasi untuk mendeteksi, menganalisis, dan merespons peristiwa keamanan.',
                'deskripsi'       => 'Menetapkan peran dan tanggung jawab organisasi pada kebijakan pendeteksian Peristiwa Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.1.2 — Melaksanakan pendeteksian Peristiwa Siber sesuai persyaratan
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-116',
                'judul'           => 'Melakukan proses pemantauan peristiwa keamanan siber, sesuai dengan peraturan, arahan, standar industri, dan aturan lainnya yang berlaku.',
                'deskripsi'       => 'Melaksanakan pendeteksian Peristiwa Siber sesuai persyaratan dan kebijakan yang berlaku',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-117',
                'judul'           => 'Melakukan pemantauan dan kontrol jaringan pada setiap titik masuk ke  jaringan organisasi.',
                'deskripsi'       => 'Melaksanakan pendeteksian Peristiwa Siber sesuai persyaratan dan kebijakan yang berlaku',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.1.3 — Menguji prosedur pendeteksian Peristiwa Siber secara berkala
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-118',
                'judul'           => 'Penyelenggara Sistem Elektronik melakukan pengujian secara periodik tentang efektifitas perangkat dan prosedur untuk mendeteksi peristiwa keamanan sebagaimana mestinya.',
                'deskripsi'       => 'Menguji prosedur pendeteksian Peristiwa Siber secara berkala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.1.4 — Menyampaikan informasi hasil pendeteksian Peristiwa Siber ke
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-119',
                'judul'           => 'Informasi hasil pendeteksian kejadian keamanan diberitahukan kepada pihak yang terkait sesuai dengan persetujuan manajemen organisasi.',
                'deskripsi'       => 'Menyampaikan informasi hasil pendeteksian Peristiwa Siber kepada pihak  yang berhak',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-120',
                'judul'           => 'Penyelenggara Sistem Elektronik melakukan reviu dan peningkatan prosedur pendeteksian peristiwa keamanan secara berkala.',
                'deskripsi'       => 'Menyampaikan informasi hasil pendeteksian Peristiwa Siber kepada pihak  yang berhak',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.2.1 — Menetapkan dan mendokumentasikan ambang batas peringatan ter
        $kategori = Kategori::where('kode_kategori', 'DE.AE')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.AE-121',
                'judul'           => 'Penyelenggara Sistem Elektronik menetapkan dan menerapkan prosedur untuk mengidentifikasi dan mengelola ambang batas terhadap operasional jaringan dan arus informasi yang diharapkan antara pengguna, penyelenggara, dan sistem.',
                'deskripsi'       => 'Menetapkan dan mendokumentasikan ambang batas peringatan terhadap insiden operasional yang diharapkan organisasi terhadap jaringan komputer dan alur data.',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.2.2 — Melaksanakan analisis terhadap Peristiwa Siber yang terdetek
        $kategori = Kategori::where('kode_kategori', 'DE.AE')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.AE-122',
                'judul'           => 'Kejadian keamanan yang terdeteksi dianalisis untuk memahami target dan metode serangan',
                'deskripsi'       => 'Melaksanakan analisis terhadap Peristiwa Siber yang terdeteksi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.AE-123',
                'judul'           => 'Identifikasi peristiwa keamanan secara akurat dengan menerapkan prosedur untuk melakukan analisis korelasi insiden keamanan dan analisis komparatif dengan informasi ancaman yang diperoleh dari luar organisasi.',
                'deskripsi'       => 'Melaksanakan analisis terhadap Peristiwa Siber yang terdeteksi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.2.3 — Menentukan dampak dari Peristiwa Siber yang terdeteksi
        $kategori = Kategori::where('kode_kategori', 'DE.AE')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.AE-124',
                'judul'           => 'Identifikasi dampak peristiwa keamanan, termasuk dampaknya terhadap organisasi lain yang relevan.',
                'deskripsi'       => 'Menentukan dampak dari Peristiwa Siber yang terdeteksi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.2.4 — Mendokumentasikan hasil analisis terhadap Peristiwa Siber ya
        $kategori = Kategori::where('kode_kategori', 'DE.AE')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.AE-125',
                'judul'           => 'Hasil analisis peristiwa siber didokumentasikan, serta dilaporkan kepada pihak manajemen sesuai ketentuan.',
                'deskripsi'       => 'Mendokumentasikan hasil analisis terhadap Peristiwa Siber yang terdeteksi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.3.1 — Menerapkan prosedur pendeteksi kode berbahaya dan tak berizi
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-126',
                'judul'           => 'gunakan perangkat teknologi yang dapat mendeteksi perilaku abnormal pada sistem dan jaringan. (misalnya perangkat intrustion detection and prevention systems, next-generation firewall, endpoint detection and response, dll)',
                'deskripsi'       => 'Menerapkan prosedur pendeteksi kode berbahaya dan tak berizin',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-127',
                'judul'           => 'memvalidasi apakah informasi atau file yang diberikan dari dunia maya tidak mengandung kode berbahaya, sebelum tindakan dilakukan.',
                'deskripsi'       => 'Menerapkan prosedur pendeteksi kode berbahaya dan tak berizin',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-128',
                'judul'           => 'memvalidasi integritas dan keaslian informasi yang diberikan dari dunia maya sebelum tindakan dilakukan.',
                'deskripsi'       => 'Menerapkan prosedur pendeteksi kode berbahaya dan tak berizin',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.3.2 — Memonitor kegiatan personel yang berada di dalam lingkup sis
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-129',
                'judul'           => 'Memastikan bahwa personel yang berada pada layanan Sistem Elektronik tidak melakukan koneksi, memasang perangkat keras ataupun perangkat lunak yang tidak berizin pada lingkup sistem Sistem Elektronik.',
                'deskripsi'       => 'Memonitor kegiatan personel yang berada di dalam lingkup sistem Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.3.3 — Memonitor kegiatan pihak ketiga yang berada di dalam lingkup
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-130',
                'judul'           => 'Memastikan bahwa pihak ketiga yang berada pada layanan Sistem Elektronik tidak melakukan koneksi, memasang perangkat keras ataupun perangkat lunak yang tidak berizin pada lingkup sistem Sistem Elektronik.',
                'deskripsi'       => 'Memonitor kegiatan pihak ketiga yang berada di dalam lingkup sistem Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 3.3.4 — Menerapkan teknologi pemindaian kerentanan terhadap sistem S
        $kategori = Kategori::where('kode_kategori', 'DE.CM')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-131',
                'judul'           => 'Memastikan bahwa seluruh perangkat teknologi pada lingkup Sistem Elektronik telah diuji keamanannya melalui penilaian kerentanan, uji penetrasi, atau audit keamanan.',
                'deskripsi'       => 'Menerapkan teknologi pemindaian kerentanan terhadap sistem Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'DE.CM-132',
                'judul'           => 'Penyelenggara Sistem Elektronik memastikan adanya pemeriksaan rutin di perangkat dan server yang dikelola dalam organisasi.',
                'deskripsi'       => 'Menerapkan teknologi pemindaian kerentanan terhadap sistem Sistem Elektronik',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.1.1 — Menyusun dan menetapkan rencana tanggap Insiden Siber yang d
        $kategori = Kategori::where('kode_kategori', 'RS.MA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-133',
                'judul'           => 'Menentukan dan menetapkan prosedur tanggap insiden beserta pembagian peran yang jelas antara pihak manajemen, personel pengelola Sistem Elektronik, dan pihak lainnya. yang mencakup tindakan yang harus dilakukan setelah mendeteksi adanya Insiden Siber.',
                'deskripsi'       => 'Menyusun dan menetapkan rencana tanggap Insiden Siber yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-134',
                'judul'           => 'Menyusun dan menetapkan prosedur rencana tanggap Insiden Siber, mulai dari tahapan persiapan, identifikasi, kontainmen, eradiksi, pemulihan, dan peningkatan berkelanjutan',
                'deskripsi'       => 'Menyusun dan menetapkan rencana tanggap Insiden Siber yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-135',
                'judul'           => 'Menentukan skenario insiden keamanan yang mungkin terjadi pada layanan Sistem Elektronik dan menambahkannya pada dokumen rencana tanggap Insiden Siber.',
                'deskripsi'       => 'Menyusun dan menetapkan rencana tanggap Insiden Siber yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-136',
                'judul'           => 'Memastikan rencana tanggap Insiden Siber dikomunikasikan kepada pihak-pihak yang berkepentingan dan berhak sesuai ketentuan.',
                'deskripsi'       => 'Menyusun dan menetapkan rencana tanggap Insiden Siber yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.1.2 — Menyusun dan menetapkan rencana keberlangsungan kegiatan yan
        $kategori = Kategori::where('kode_kategori', 'RS.MA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-137',
                'judul'           => 'Menentukan dan menetapkan daftar fungsi dan layanan vital bagi penyelenggaraan Sistem Elektronik, beserta daftar pembagian peran dan tanggung jawab dengan pihak-pihak yang berhubungan dengan operasional layanan Sistem Elektronik.',
                'deskripsi'       => 'Menyusun dan menetapkan rencana keberlangsungan kegiatan yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-138',
                'judul'           => 'Menentukan dan menetapkan strategi, tahapan, beserta target waktu yang dibutuhkan untuk memulihkan dan menjalankan fungsi dan layanan vital secara penuh/kembali normal.',
                'deskripsi'       => 'Menyusun dan menetapkan rencana keberlangsungan kegiatan yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-139',
                'judul'           => 'Menentukan daftar sumber daya, peralatan, dan personil yang dibutuhkan untuk menjalankan fungsi dan layanan vital.',
                'deskripsi'       => 'Menyusun dan menetapkan rencana keberlangsungan kegiatan yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-140',
                'judul'           => 'Memastikan rencana keberlangsungan layanan dikomunikasikan kepada pihak-pihak yang berkepentingan dan berhak sesuai ketentuan.',
                'deskripsi'       => 'Menyusun dan menetapkan rencana keberlangsungan kegiatan yang disetujui oleh pimpinan organisasi',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.1.3 — Memastikan rencana tanggap Insiden Siber dan rencana keberla
        $kategori = Kategori::where('kode_kategori', 'RS.MA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-141',
                'judul'           => 'Memastikan rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan disimulasikan secara berkala oleh seluruh pihak yang terlibat dalam lingkup Sistem Elektronik. (misalnya dalam kegiatan simulasi kesiapsiagaan Insiden Siber)',
                'deskripsi'       => 'Memastikan rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan dilaksanakan dan disimulasikan secara berkala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.1.4 — Memastikan personel yang mengelola Sistem Elektronik mengeta
        $kategori = Kategori::where('kode_kategori', 'RS.MA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-142',
                'judul'           => 'Organisasi menentukan dan menetapkan personel yang ditugaskan dalam Tim Tanggap Insiden Siber.',
                'deskripsi'       => 'Memastikan personel yang mengelola Sistem Elektronik mengetahui peran dan prosedur penanggulangan dan pemulihan sesuai rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-143',
                'judul'           => 'Organisasi memastikan personel mengetahui perannya dan urutan pengoperasian bila respons diperlukan',
                'deskripsi'       => 'Memastikan personel yang mengelola Sistem Elektronik mengetahui peran dan prosedur penanggulangan dan pemulihan sesuai rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-144',
                'judul'           => 'Organisasi mengidentifikasi siapa saja pihak-pihak pihak-pihak terkait dalam proses penanggulangan dan pemulihan insiden, misalnya aparat penegak hukum, regulator, maupun tim TTIS nasional',
                'deskripsi'       => 'Memastikan personel yang mengelola Sistem Elektronik mengetahui peran dan prosedur penanggulangan dan pemulihan sesuai rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-145',
                'judul'           => 'Organisasi perlu mengembangkan dan mengelola aturan mengenai penerbitan dan distribusi informasi setelah terjadinya insiden keamanan. sehingga informasi organisasi hanya boleh keluar melalui personel yang berwenang saja.',
                'deskripsi'       => 'Memastikan personel yang mengelola Sistem Elektronik mengetahui peran dan prosedur penanggulangan dan pemulihan sesuai rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.1.5 — Memastikan personel yang mengelola Sistem Elektronik memaham
        $kategori = Kategori::where('kode_kategori', 'RS.MA')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.MA-146',
                'judul'           => 'Organisasi memastikan personel yang mengelola Sistem Elektronik melakukan prosedur rekam cadang untuk mengamankan aset informasi berupa sistem/data yang tersimpan di dalam sistem Sistem Elektronik, serta memastikan bahwa media yang digunakan untuk menyimpan data tersebut telah diamankan.',
                'deskripsi'       => 'Memastikan personel yang mengelola Sistem Elektronik memahami prosedur penggunaan rekam cadang.',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.2.1 — Mengumpulkan informasi kondisi Sistem Elektronik terkini bai
        $kategori = Kategori::where('kode_kategori', 'RS.AN')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.AN-147',
                'judul'           => 'Organisasi memastikan hasil analisis deteksi peristiwa siber diperiksa untuk menilai ada atau tidaknya anomali pada sistem.',
                'deskripsi'       => 'Mengumpulkan informasi kondisi Sistem Elektronik terkini baik dari hasil deteksi internal maupun sumber informasi eksternal',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.AN-148',
                'judul'           => 'Organisasi mengumpulkan dan menganalisis laporan peristiwa siber yang diterima baik dari pengguna layanan, maupun sumber eksternal organisasi misalnya laporan dari Tim Tanggap Insiden Siber nasional atau mitra pihak ketiga.',
                'deskripsi'       => 'Mengumpulkan informasi kondisi Sistem Elektronik terkini baik dari hasil deteksi internal maupun sumber informasi eksternal',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.2.2 — Mengidentifikasi dan menganalisis potensi dampak dari Inside
        $kategori = Kategori::where('kode_kategori', 'RS.AN')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.AN-149',
                'judul'           => 'Organisasi harus mengidentifikasi dan menganalisis potensi dampak Insiden Siber pada layanan Sistem Elektronik, termasuk organisasi, dan pihak terkait seperti mitra ketiga berdasarkan laporan lengkap Insiden Siber.',
                'deskripsi'       => 'Mengidentifikasi dan menganalisis potensi dampak dari Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.2.3 — Memastikan Insiden Siber dikategorikan sesuai kriteria yang 
        $kategori = Kategori::where('kode_kategori', 'RS.AN')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.AN-150',
                'judul'           => 'Organisasi memastikan laporan Insiden Siber dikumpulkan, dikategorisasikan, dan diprioritaskan sesuai dampak risiko terhadap organisasi',
                'deskripsi'       => 'Memastikan Insiden Siber dikategorikan sesuai kriteria yang telah ditetapkan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.2.4 — Memastikan bahwa Insiden Siber dilaporkan kepada pihak yang 
        $kategori = Kategori::where('kode_kategori', 'RS.AN')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.AN-151',
                'judul'           => 'Organisasi memastikan informasi mengenai Insiden Siber dilaporkan kepada pihak yang berwenang sesuai dengan kriteria yang ditetapkan oleh organisasi dan peraturan perundangan yang berlaku.',
                'deskripsi'       => 'Memastikan bahwa Insiden Siber dilaporkan kepada pihak yang terkait',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RS.AN-152',
                'judul'           => 'Organisasi memastikan proses koordinasi dengan para pemangku kepentingan dilakukan sesuai dengan rencana tanggap Insiden Siber (seperti kepada Kementerian atau Lembaga pembina sektor, Tim Tanggap Insiden Siber nasional, atau mitra pihak ketiga)',
                'deskripsi'       => 'Memastikan bahwa Insiden Siber dilaporkan kepada pihak yang terkait',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.1 — Memastikan Insiden Siber diisolasi dan dimitigasi sesuai ren
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-153',
                'judul'           => 'Organisasi harus mengambil langkah-langkah yang diperlukan untuk meminimalkan kerusakan terkait keamanan dan mengurangi dampak yang disebabkan oleh Insiden Siber tersebut. Misalnya melakukan isolasi terhadap jaringan yang terdapat Insiden Siber.',
                'deskripsi'       => 'Memastikan Insiden Siber diisolasi dan dimitigasi sesuai rencana tanggap Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-154',
                'judul'           => 'Organisasi harus mengidentifikasi hal-hal yang perlu diprioritaskan dan ruang lingkup respons yang perlu diambil.',
                'deskripsi'       => 'Memastikan Insiden Siber diisolasi dan dimitigasi sesuai rencana tanggap Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-155',
                'judul'           => 'Organisasi harus mengambil tindakan yang tepat terhadap perangkat yang terpengaruh oleh Insiden Siber, terutama mengenai fasilitas produksi yang rusak akibat insiden keamanan.',
                'deskripsi'       => 'Memastikan Insiden Siber diisolasi dan dimitigasi sesuai rencana tanggap Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-156',
                'judul'           => 'Organisasi mendokumentasikan hasil mitigasi Insiden Siber tersebut sebagai bahan pembelajaran berkelanjutan.',
                'deskripsi'       => 'Memastikan Insiden Siber diisolasi dan dimitigasi sesuai rencana tanggap Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.2 — Mengumpulkan dan memelihara bukti Insiden Siber dari Sistem 
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-157',
                'judul'           => 'Organisasi memastikan informasi mengenai insiden keamanan yang terdeteksi dikategorikan dan disimpan menurut ukuran dampak terkait keamanan, penyebab insiden, dan faktor lainnya yang diperlukan.',
                'deskripsi'       => 'Mengumpulkan dan memelihara bukti Insiden Siber dari Sistem Elektronik terdampak',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.3 — Menginvestigasi dan eradikasi penyebab Insiden Siber
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-158',
                'judul'           => 'Untuk keperluan investigasi dan/atau audit, maka Organisasi menerapkan forensik digital terhadap aset informasi yang terdampak insiden keamanan untuk menemukenali penyebab insiden.',
                'deskripsi'       => 'Menginvestigasi dan eradikasi penyebab Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-159',
                'judul'           => 'Organisasi harus memastikan bahwa seluruh aset informasi yang terdampak Insiden Siber telah diperiksa setiap komponennya untuk menghapus setiap kode berbahaya atau indikasi ancaman lainnya yang terkait Insiden Siber',
                'deskripsi'       => 'Menginvestigasi dan eradikasi penyebab Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.4 — Mengoordinasikan dengan pihak terkait dalam rangka eskalasi 
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-160',
                'judul'           => 'jika insiden meningkat atau meluas, maka Organisasi perlu menyiapkan dan melaksanakan prosedur eskalasi Insiden Siber, seperti melaporkan kepada tim tanggap Insiden Siber (TTIS) sektoral dan nasional, serta menyiapkan informasi yang relevan terkait Insiden Siber tersebut.',
                'deskripsi'       => 'Mengoordinasikan dengan pihak terkait dalam rangka eskalasi penanggulangan Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.5 — Memastikan setiap aset informasi diperiksa keamanannya setel
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-161',
                'judul'           => 'Organisasi melakukan pemeriksaan seluruh aset informasi yang berhubungan dengan Sistem Elektronik untuk memastikan seluruh sistem tersebut telah bersih dari indikasi ancaman atau serangan yang telah terjadi',
                'deskripsi'       => 'Memastikan setiap aset informasi diperiksa keamanannya setelah penanganan Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.6 — Melaksanakan prosedur pencadangan dan pemulihan sistem dan d
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-162',
                'judul'           => 'Organisasi melaksanakan prosedur pemulihan sistem/data dari media penyimpanan dalam hal terjadi keadaan darurat.',
                'deskripsi'       => 'Melaksanakan prosedur pencadangan dan pemulihan sistem dan data sesuai rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-163',
                'judul'           => 'Organisasi melaksanakan simulasi terhadap prosedur pemulihan sistem/data dari media penyimpangan rekam cadang secara periodik.',
                'deskripsi'       => 'Melaksanakan prosedur pencadangan dan pemulihan sistem dan data sesuai rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-164',
                'judul'           => 'prosedur rekam cadang (back-up) sedapat mungkin dilakukan secara otomatis dengan memanfaatkan perangkat-perangkat penyimpanan yang mempunyai fitur job-schedulling.',
                'deskripsi'       => 'Melaksanakan prosedur pencadangan dan pemulihan sistem dan data sesuai rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-165',
                'judul'           => 'Organisasi memastikan data yang disimpan pada media penyimpanan rekam cadang diamankan menggunakan enkripsi.',
                'deskripsi'       => 'Melaksanakan prosedur pencadangan dan pemulihan sistem dan data sesuai rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-166',
                'judul'           => 'Organisasi menentukan waktu pelaksanaan rekam cadang terhadap data organisasi yang disesuaikan dengan tingkat kritikalitas data dan kebutuhan organisasi.',
                'deskripsi'       => 'Melaksanakan prosedur pencadangan dan pemulihan sistem dan data sesuai rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-167',
                'judul'           => 'Organisasi memastikan hasil pelaksanaan rekam cadang data didokumentasikan.',
                'deskripsi'       => 'Melaksanakan prosedur pencadangan dan pemulihan sistem dan data sesuai rencana keberlangsungan kegiatan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.7 — Menentukan dan menerapkan retensi terhadap hasil pencadangan
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-168',
                'judul'           => 'Organisasi memastikan media penyimpanan rekam cadang telah disimpan secara aman.',
                'deskripsi'       => 'Menentukan dan menerapkan retensi terhadap hasil pencadangan yang sudah tidak terpakai sesuai ketentuan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-169',
                'judul'           => 'pemusnahan terhadap data yang disimpan pada media rekam cadang harus dilaksanakan dengan persetujuan pimpinan organisasi.',
                'deskripsi'       => 'Menentukan dan menerapkan retensi terhadap hasil pencadangan yang sudah tidak terpakai sesuai ketentuan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-170',
                'judul'           => 'pemusnahan data pada media rekam cadang dilakukan dengan melakukan format ulang atas media rekam cadang dan memastikan bahwa data tersebut tidak dapat diakses lagi.',
                'deskripsi'       => 'Menentukan dan menerapkan retensi terhadap hasil pencadangan yang sudah tidak terpakai sesuai ketentuan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.8 — Pengujian ulang terhadap fungsi vital dan fungsi pendukung u
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-171',
                'judul'           => 'Organisasi harus menyusun dan menerapkan prosedur yang bertujuan untuk memastikan seluruh fungsi pada layanan Sistem Elektronik telah beroperasi dengan normal pasca Insiden Siber',
                'deskripsi'       => 'Pengujian ulang terhadap fungsi vital dan fungsi pendukung untuk memastikan capaian pemulihan terpenuhi.',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.9 — Memastikan organisasi memiliki dan mengelola strategi komuni
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-172',
                'judul'           => 'Organisasi perlu menyusun dan menerapkan strategi komunikasi publik dalam hal mengelola informasi yang perlu disampaikan terkait Insiden Siber.',
                'deskripsi'       => 'Memastikan organisasi memiliki dan mengelola strategi komunikasi publik ketika terjadi Insiden Siber dan setelah penanggulangan serta pemulihan Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-173',
                'judul'           => 'Organisasi memastikan bahwa proses penanganan dan pemulihan insiden dikomunikasikan dengan pihak yang berkepentingan sesuai dengan peraturan perundangan.',
                'deskripsi'       => 'Memastikan organisasi memiliki dan mengelola strategi komunikasi publik ketika terjadi Insiden Siber dan setelah penanggulangan serta pemulihan Insiden Siber',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.3.10 — Penyampaian informasi penanggulangan dan pemulihan Insiden S
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-174',
                'judul'           => 'Organisasi menyusun laporan hasil penanganan Insiden Siber dan menyampaikannya kepada Kementerian atau Lembaga di masing-masing sektor.',
                'deskripsi'       => 'Penyampaian informasi penanggulangan dan pemulihan Insiden Siber kepada pihak terkait',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.4.1 — Meninjau kembali efektifitas Kontrol Keamanan yang telah dit
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-175',
                'judul'           => 'Organisasi mengevaluasi kontrol keamanan yang diterapkan apakah masih relevan terhadap spektrum ancaman yang ada atau perlu ada perbaikan dan penambahan.',
                'deskripsi'       => 'Meninjau kembali efektifitas Kontrol Keamanan yang telah diterapkan',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.4.2 — Mereviu dan/atau memperbarui dokumen rencana tanggap Insiden
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-176',
                'judul'           => 'Organisasi melakukan reviu dan pembaharuan terhadap dokumen rencana tanggap Insiden Siber dan pemulihan jika terdapat hal-hal yang dapat dijadikan pembelajaran berkelanjutan bagi organisasi.',
                'deskripsi'       => 'Mereviu dan/atau memperbarui dokumen rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan secara berkala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-177',
                'judul'           => 'Organisasi mendokumentasikan kerentanan, ancaman, atau risiko yang baru ditemukan beserta rencana mitigasinya ke dalam dokumen pengelolaan risiko',
                'deskripsi'       => 'Mereviu dan/atau memperbarui dokumen rencana tanggap Insiden Siber dan rencana keberlangsungan kegiatan secara berkala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.4.3 — Mengumpulkan dan memelihara hasil forensik digital
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-178',
                'judul'           => 'Laporan hasil pelaksanaan forensik digital dikumpulkan dan dipelihara meliputi juga informasi-informasi yang relevan terhadapnya.',
                'deskripsi'       => 'Mengumpulkan dan memelihara hasil forensik digital',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-179',
                'judul'           => 'Laporan hasil pelaksanaan forensik digital dapat disampaikan kepada pihak berwajib untuk proses investigasi dan penegakkan hukum sesuai ketentuan yang berlaku',
                'deskripsi'       => 'Mengumpulkan dan memelihara hasil forensik digital',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Sub Kategori 4.4.4 — Meninjau efektivitas kinerja penanganan insiden yang dilakuk
        $kategori = Kategori::where('kode_kategori', 'RC.RP')->first();
        if ($kategori) {
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-180',
                'judul'           => 'Organisasi melakukan peninjauan terhadap efektifitas kinerja penanganan insiden yang dilakukan oleh tim tanggap Insiden Siber secara berkala.',
                'deskripsi'       => 'Meninjau efektivitas kinerja penanganan insiden yang dilakukan oleh tim tanggap Insiden Siber secara berkala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
            $pertanyaans[] = [
                'kategori_id'     => $kategori->kategori_id,
                'kode_pertanyaan' => 'RC.RP-181',
                'judul'           => 'Melakukan langkah-langkah perbaikan yang diperlukan terhadap pelaksanaan penanganan Insiden Siber meliputi dari segi teknologi, tata kelola, atau peningkatan kapasistas SDM.',
                'deskripsi'       => 'Meninjau efektivitas kinerja penanganan insiden yang dilakukan oleh tim tanggap Insiden Siber secara berkala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Insert semua sekaligus
        foreach (array_chunk($pertanyaans, 50) as $chunk) {
            DB::table('pertanyaans')->insert($chunk);
        }
    }
}