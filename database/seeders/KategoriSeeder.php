<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            // GOVERN (domain_id: 1)
            [1, 'GV.OC', 'Organizational Context', 'Memahami konteks organisasi dalam keamanan siber'],
            [1, 'GV.RM', 'Risk Management Strategy', 'Strategi manajemen risiko keamanan siber'],
            [1, 'GV.RR', 'Roles, Responsibilities & Authorities', 'Penetapan peran dan tanggung jawab keamanan siber'],
            [1, 'GV.PO', 'Policy', 'Kebijakan keamanan siber organisasi'],
            [1, 'GV.OV', 'Oversight', 'Pengawasan program keamanan siber'],
            [1, 'GV.SC', 'Supply Chain Risk Management', 'Manajemen risiko rantai pasok siber'],

            // IDENTIFY (domain_id: 2)
            [2, 'ID.AM', 'Asset Management', 'Mengelola aset fisik dan digital organisasi'],
            [2, 'ID.RA', 'Risk Assessment', 'Menilai risiko keamanan siber'],
            [2, 'ID.IM', 'Improvement', 'Peningkatan berkelanjutan postur keamanan siber'],

            // PROTECT (domain_id: 3)
            // ---> INI CONTOH YANG SUDAH DIISI TEKS INDEKS 0-5 DARI GAMBARMU <---
            [3, 'PR.AA', 'Identity Management & Access Control', 'Mengelola identitas, autentikasi, dan kendali akses',
                'Belum memiliki prosedur pengelolaan aset informasi dan personel yang menggunakan IIV', // indeks 0
                'Prosedur pengelolaan aset informasi dan personel yang menggunakan IIV sudah diterapkan pada sebagian kecil aspek namun belum diformalkan', // indeks 1
                'Prosedur pengelolaan aset informasi dan personel yang menggunakan IIV sudah diterapkan pada sebagian besar aspek namun belum diformalkan', // indeks 2
                'Prosedur pengelolaan aset informasi dan personel yang menggunakan IIV sudah diformalkan dan diterapkan pada seluruh aspek', // indeks 3
                'Prosedur pengelolaan aset informasi dan personel yang menggunakan IIV sudah diformalkan dan diterapkan pada seluruh aspek. Prosedur tersebut dimonitor pelaksanaanya dan direview secara berkala.', // indeks 4
                'Prosedur pengelolaan aset informasi dan personel yang menggunakan IIV sudah diformalkan dan diterapkan pada seluruh aspek. Prosedur tersebut dimonitor pelaksanaanya dan direview secara berkala, serta dilakukan perbaikan secara berkelanjutan.' // indeks 5
            ],
            
            [3, 'PR.AT', 'Awareness & Training', 'Pelatihan dan kesadaran keamanan siber SDM'],
            [3, 'PR.DS', 'Data Security', 'Melindungi data organisasi'],
            [3, 'PR.PS', 'Platform Security', 'Melindungi perangkat keras, perangkat lunak, dan layanan'],
            [3, 'PR.IR', 'Technology Infrastructure Resilience', 'Ketahanan infrastruktur teknologi'],

            // DETECT (domain_id: 4)
            [4, 'DE.CM', 'Continuous Monitoring', 'Pemantauan aset dan ancaman secara berkelanjutan'],
            [4, 'DE.AE', 'Adverse Event Analysis', 'Menganalisis anomali dan peristiwa siber'],

            // RESPOND (domain_id: 5)
            [5, 'RS.MA', 'Incident Management', 'Pengelolaan respons insiden siber'],
            [5, 'RS.AN', 'Incident Analysis', 'Menganalisis dan melaporkan insiden siber'],
            [5, 'RS.CO', 'Incident Response Reporting', 'Pelaporan dan komunikasi insiden'],
            [5, 'RS.MI', 'Incident Mitigation', 'Mitigasi dampak insiden siber'],

            // RECOVER (domain_id: 6)
            [6, 'RC.RP', 'Incident Recovery Plan', 'Perencanaan pemulihan insiden siber'],
            [6, 'RC.CO', 'Incident Recovery Communication', 'Komunikasi pemulihan pasca insiden'],
        ];

        foreach ($kategoris as $k) {
            DB::table('kategoris')->insert([
                'domain_id'      => $k[0],
                'kode_kategori'  => $k[1],
                'nama_kategori'  => $k[2],
                'deskripsi'      => $k[3],
                // Logika baru: Jika ada isi array ke-4 s/d ke-9, masukkan ke database. Jika tidak, jadikan null.
                'indeks_0'       => $k[4] ?? null,
                'indeks_1'       => $k[5] ?? null,
                'indeks_2'       => $k[6] ?? null,
                'indeks_3'       => $k[7] ?? null,
                'indeks_4'       => $k[8] ?? null,
                'indeks_5'       => $k[9] ?? null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}