<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\SkemaBantuan;
use App\Models\Peneliti;
use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\Pkm;
use App\Models\Buku;
use App\Models\Haki;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fakultas
        $fasih = Fakultas::create(['nama' => 'Fakultas Syariah', 'kode' => 'FASIH']);
        $ftik = Fakultas::create(['nama' => 'Fakultas Tarbiyah dan Ilmu Keguruan', 'kode' => 'FTIK']);
        $febi = Fakultas::create(['nama' => 'Fakultas Ekonomi dan Bisnis Islam', 'kode' => 'FEBI']);
        $fuad = Fakultas::create(['nama' => 'Fakultas Ushuluddin, Adab, dan Dakwah', 'kode' => 'FUAD']);
        $pasca = Fakultas::create(['nama' => 'Pascasarjana', 'kode' => 'PASCA']);

        // 2. Prodi
        $p1 = Prodi::create(['fakultas_id' => $fasih->id, 'nama' => 'Hukum Keluarga Islam (Ahwal Syakhshiyyah)', 'kode' => 'HKI', 'jenjang' => 'S1']);
        $p2 = Prodi::create(['fakultas_id' => $fasih->id, 'nama' => 'Hukum Ekonomi Syariah (Muamalah)', 'kode' => 'HES', 'jenjang' => 'S1']);
        $p3 = Prodi::create(['fakultas_id' => $ftik->id, 'nama' => 'Pendidikan Agama Islam', 'kode' => 'PAI', 'jenjang' => 'S1']);
        $p4 = Prodi::create(['fakultas_id' => $ftik->id, 'nama' => 'Pendidikan Bahasa Arab', 'kode' => 'PBA', 'jenjang' => 'S1']);
        $p5 = Prodi::create(['fakultas_id' => $ftik->id, 'nama' => 'Manajemen Pendidikan Islam', 'kode' => 'MPI', 'jenjang' => 'S1']);
        $p6 = Prodi::create(['fakultas_id' => $febi->id, 'nama' => 'Perbankan Syariah', 'kode' => 'PS', 'jenjang' => 'S1']);
        $p7 = Prodi::create(['fakultas_id' => $febi->id, 'nama' => 'Ekonomi Syariah', 'kode' => 'ES', 'jenjang' => 'S1']);
        $p8 = Prodi::create(['fakultas_id' => $fuad->id, 'nama' => 'Ilmu Al-Qur\'an dan Tafsir', 'kode' => 'IAT', 'jenjang' => 'S1']);
        $p9 = Prodi::create(['fakultas_id' => $fuad->id, 'nama' => 'Komunikasi dan Penyiaran Islam', 'kode' => 'KPI', 'jenjang' => 'S1']);
        $p10 = Prodi::create(['fakultas_id' => $pasca->id, 'nama' => 'Magister Pendidikan Agama Islam', 'kode' => 'MPAI', 'jenjang' => 'S2']);

        // 3. Skema Bantuan
        $s1 = SkemaBantuan::create(['nama' => 'Penelitian Pembinaan Kapasitas Dosen', 'jenis' => 'penelitian']);
        $s2 = SkemaBantuan::create(['nama' => 'Penelitian Terapan dan Pengembangan Kebijakan', 'jenis' => 'penelitian']);
        $s3 = SkemaBantuan::create(['nama' => 'Penelitian Kolaboratif Antar Perguruan Tinggi', 'jenis' => 'penelitian']);
        $s4 = SkemaBantuan::create(['nama' => 'PKM Berbasis Komunitas dan Moderasi Beragama', 'jenis' => 'pkm']);
        $s5 = SkemaBantuan::create(['nama' => 'PKM Pemberdayaan Ekonomi Pesantren dan UMKM', 'jenis' => 'pkm']);

        // 4. Peneliti Dummy
        $penelitiList = [
            [
                'nama_lengkap' => 'Prof. Dr. H. Ahmad Zainal, M.Ag.',
                'nidn' => '2015087101',
                'prodi_id' => $p1->id,
                'email' => 'ahmad.zainal@iain-manado.ac.id',
                'bidang_keahlian' => 'Hukum Islam, Fiqh Kontemporer, Resolusi Konflik Adat',
                'sinta_id' => '6012455',
                'scopus_id' => '57201948271',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Dr. Siti Rahmah, S.Ag., M.Pd.I.',
                'nidn' => '2004128502',
                'prodi_id' => $p3->id,
                'email' => 'siti.rahmah@iain-manado.ac.id',
                'bidang_keahlian' => 'Pendidikan Inklusif, Kurikulum Merdeka, Literasi Digital Anak',
                'sinta_id' => '6048123',
                'scopus_id' => '57211837482',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Muhammad Farid, S.E., M.E.K.',
                'nidn' => '2023038803',
                'prodi_id' => $p6->id,
                'email' => 'm.farid@iain-manado.ac.id',
                'bidang_keahlian' => 'Fintech Syariah, Perbankan Digital, Manajemen Risiko',
                'sinta_id' => '6089234',
                'scopus_id' => '',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Dr. Nurul Hidayah, M.Hum.',
                'nidn' => '2011097904',
                'prodi_id' => $p8->id,
                'email' => 'nurul.hidayah@iain-manado.ac.id',
                'bidang_keahlian' => 'Tafsir Nusantara, Kajian Gender & Al-Qur\'an, Filologi Islam',
                'sinta_id' => '6033491',
                'scopus_id' => '57193827461',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Faisal Basri, M.Sos.',
                'nidn' => '2018079205',
                'prodi_id' => $p9->id,
                'email' => 'faisal.basri@iain-manado.ac.id',
                'bidang_keahlian' => 'Komunikasi Antarbudaya, Moderasi Beragama di Media Sosial',
                'sinta_id' => '6078120',
                'scopus_id' => '',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Prof. Dr. Irfan Mokodompit, M.Si.',
                'nidn' => '2001046806',
                'prodi_id' => $p10->id,
                'email' => 'irfan.moko@iain-manado.ac.id',
                'bidang_keahlian' => 'Sosiologi Pendidikan Islam, Kebijakan Publik, Kepemimpinan Transformatif',
                'sinta_id' => '5982109',
                'scopus_id' => '57002938472',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Hussein Al-Kaff, S.E.I., M.Si.',
                'nidn' => '2020119007',
                'prodi_id' => $p7->id,
                'email' => 'hussein.alkaff@iain-manado.ac.id',
                'bidang_keahlian' => 'Ekonomi Pembangunan, Filantropi Islam, Zakat & Wakaf Produktif',
                'sinta_id' => '6102938',
                'scopus_id' => '',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Dr. Hj. Mardiana, M.Pd.',
                'nidn' => '2014027708',
                'prodi_id' => $p5->id,
                'email' => 'mardiana@iain-manado.ac.id',
                'bidang_keahlian' => 'Manajemen Mutu Madrasah, Supervisi Akademik, Evaluasi Pendidikan',
                'sinta_id' => '6029381',
                'scopus_id' => '57220194832',
                'status' => 'aktif',
            ],
        ];

        $createdPeneliti = [];
        foreach ($penelitiList as $item) {
            $createdPeneliti[] = Peneliti::create($item);
        }

        // 5. Penelitian Dummy
        $penelitianData = [
            [
                'nomor' => 'LP2M/LIT/2026/001',
                'skema_bantuan_id' => $s2->id,
                'judul' => 'Integrasi Nilai Kearifan Lokal "Torang Samua Basudara" dalam Penguatan Fiqh Kerukunan Umat Beragama di Manado',
                'tahun' => 2026,
                'author_idx' => [0, 4],
            ],
            [
                'nomor' => 'LP2M/LIT/2026/002',
                'skema_bantuan_id' => $s1->id,
                'judul' => 'Efektivitas Model Pembelajaran Berbasis AI Generatif pada Mata Kuliah Pendidikan Agama Islam di Era Kurikulum Merdeka',
                'tahun' => 2026,
                'author_idx' => [1, 7],
            ],
            [
                'nomor' => 'LP2M/LIT/2025/014',
                'skema_bantuan_id' => $s3->id,
                'judul' => 'Model Akselerasi Inklusi Keuangan Syariah Berbasis Peer-to-Peer Lending pada Pelaku UMKM Pesisir Sulawesi Utara',
                'tahun' => 2025,
                'author_idx' => [2, 6],
            ],
            [
                'nomor' => 'LP2M/LIT/2025/028',
                'skema_bantuan_id' => $s2->id,
                'judul' => 'Genealogi Manuskrip Tafsir Nusantara: Kajian Naskah Kuno Keagamaan di Wilayah Bolaang Mongondow',
                'tahun' => 2025,
                'author_idx' => [3, 0],
            ],
            [
                'nomor' => 'LP2M/LIT/2025/033',
                'skema_bantuan_id' => $s3->id,
                'judul' => 'Transformasi Digital Manajemen Madrasah Aliyah di Kawasan Indonesia Timur: Pendekatan Teori Sistem Mutu',
                'tahun' => 2025,
                'author_idx' => [7, 5],
            ],
        ];

        foreach ($penelitianData as $data) {
            $p = Penelitian::create([
                'nomor' => $data['nomor'],
                'skema_bantuan_id' => $data['skema_bantuan_id'],
                'judul' => $data['judul'],
                'tahun' => $data['tahun'],
            ]);
            foreach ($data['author_idx'] as $order => $idx) {
                $p->penelitis()->attach($createdPeneliti[$idx]->id, [
                    'urutan' => $order + 1,
                    'peran' => $order === 0 ? 'Ketua Peneliti' : 'Anggota Peneliti',
                ]);
            }
        }

        // 6. Publikasi Dummy
        $publikasiData = [
            [
                'jenis_publikasi' => 'jurnal',
                'kategori_indeks' => 'Scopus Q1',
                'judul' => 'Interfaith Tolerance and Religious Moderation in Pluralistic Society: A Case Study of Northern Sulawesi',
                'tahun' => 2026,
                'nama_jurnal_prosiding' => 'Journal of Religion, Culture and Society (Scopus Indexed)',
                'tautan_doi' => 'https://doi.org/10.1016/j.relcs.2026.01.004',
                'author_idx' => [0, 5],
            ],
            [
                'jenis_publikasi' => 'jurnal',
                'kategori_indeks' => 'SINTA 2',
                'judul' => 'Implementasi Pendekatan Differentiated Learning dalam Pembelajaran PAI di Sekolah Menengah',
                'tahun' => 2026,
                'nama_jurnal_prosiding' => 'Jurnal Pendidikan Islam Indonesia (SINTA 2)',
                'tautan_doi' => 'https://doi.org/10.24252/jpii.v12i1.2026',
                'author_idx' => [1],
            ],
            [
                'jenis_publikasi' => 'jurnal',
                'kategori_indeks' => 'Scopus Q2',
                'judul' => 'Adoption of Islamic Financial Technology among Coastal Micro-Enterprises: An Empirical Investigation',
                'tahun' => 2025,
                'nama_jurnal_prosiding' => 'International Journal of Islamic and Middle Eastern Finance and Management',
                'tautan_doi' => 'https://doi.org/10.1108/IMEFM-08-2025-0321',
                'author_idx' => [2, 6],
            ],
            [
                'jenis_publikasi' => 'prosiding',
                'kategori_indeks' => 'Internasional',
                'judul' => 'Gender Perspectives in Contemporary Quranic Hermeneutics: Trends and Challenges in Southeast Asia',
                'tahun' => 2025,
                'nama_jurnal_prosiding' => 'Proceedings of the 4th International Conference on Islamic Studies (ICONIS 2025)',
                'tautan_doi' => 'https://doi.org/10.2991/iconis-25.2025.18',
                'author_idx' => [3],
            ],
        ];

        foreach ($publikasiData as $data) {
            $pub = Publikasi::create([
                'jenis_publikasi' => $data['jenis_publikasi'],
                'kategori_indeks' => $data['kategori_indeks'],
                'judul' => $data['judul'],
                'tahun' => $data['tahun'],
                'nama_jurnal_prosiding' => $data['nama_jurnal_prosiding'],
                'tautan_doi' => $data['tautan_doi'],
            ]);
            foreach ($data['author_idx'] as $order => $idx) {
                $pub->penuliss()->attach($createdPeneliti[$idx]->id, [
                    'urutan' => $order + 1,
                    'peran' => $order === 0 ? 'Penulis Pertama' : 'Penulis Korespondensi',
                ]);
            }
        }

        // 7. PKM Dummy
        $pkmData = [
            [
                'nomor' => 'LP2M/PKM/2026/005',
                'skema_bantuan_id' => $s4->id,
                'judul' => 'Penguatan Literasi Digital dan Moderasi Beragama Bagi Remaja Masjid dan Pemuda Gereja di Kota Tomohon',
                'tahun' => 2026,
                'author_idx' => [4, 0],
            ],
            [
                'nomor' => 'LP2M/PKM/2025/012',
                'skema_bantuan_id' => $s5->id,
                'judul' => 'Pendampingan Sertifikasi Halal Gratis (SEHATI) dan Manajemen Akuntansi Sederhana bagi Pelaku Usaha Kuliner Halal Manado',
                'tahun' => 2025,
                'author_idx' => [6, 2],
            ],
            [
                'nomor' => 'LP2M/PKM/2025/019',
                'skema_bantuan_id' => $s4->id,
                'judul' => 'Pelatihan Desain Bahan Ajar Interaktif Berbasis Canva dan Quizizz untuk Guru PAI Se-Kabupaten Minahasa',
                'tahun' => 2025,
                'author_idx' => [1, 7],
            ],
        ];

        foreach ($pkmData as $data) {
            $pkm = Pkm::create([
                'nomor' => $data['nomor'],
                'skema_bantuan_id' => $data['skema_bantuan_id'],
                'judul' => $data['judul'],
                'tahun' => $data['tahun'],
            ]);
            foreach ($data['author_idx'] as $order => $idx) {
                $pkm->pengabdis()->attach($createdPeneliti[$idx]->id, [
                    'urutan' => $order + 1,
                    'peran' => $order === 0 ? 'Ketua Pengabdi' : 'Anggota',
                ]);
            }
        }

        // 8. Buku Dummy
        $bukuData = [
            [
                'judul' => 'Rekonstruksi Fiqh Muamalah Kontemporer: Kajian Akad Digital dan Mata Uang Kripto',
                'nama_penerbit' => 'Rajawali Pers / IAIN Manado Press',
                'tahun' => 2026,
                'isbn' => '978-623-01-3849-1',
                'author_idx' => [0],
            ],
            [
                'judul' => 'Desain Pembelajaran Agama Islam Berbasis Neurosains',
                'nama_penerbit' => 'Kencana Prenada Media Group',
                'tahun' => 2025,
                'isbn' => '978-602-422-918-0',
                'author_idx' => [1, 7],
            ],
            [
                'judul' => 'Tafsir Tematik Gender: Meluruskan Bias Patriarki dalam Pemahaman Teks Suci',
                'nama_penerbit' => 'Mizan Pustaka',
                'tahun' => 2025,
                'isbn' => '978-979-433-882-5',
                'author_idx' => [3],
            ],
        ];

        foreach ($bukuData as $data) {
            $buku = Buku::create([
                'judul' => $data['judul'],
                'nama_penerbit' => $data['nama_penerbit'],
                'tahun' => $data['tahun'],
                'isbn' => $data['isbn'],
            ]);
            foreach ($data['author_idx'] as $order => $idx) {
                $buku->penuliss()->attach($createdPeneliti[$idx]->id, [
                    'urutan' => $order + 1,
                ]);
            }
        }

        // 9. HKI Dummy
        $hakiData = [
            [
                'judul_ciptaan' => 'Modul Panduan Praktis Resolusi Sengketa Kewarisan Adat dan Islam di Minahasa',
                'jenis_ciptaan' => 'Buku Panduan / Karya Tulis',
                'nomor_hki' => 'EC00202604812',
                'pemegang_hak_cipta' => 'IAIN Manado & Prof. Dr. Ahmad Zainal',
                'tahun' => 2026,
                'author_idx' => [0],
            ],
            [
                'judul_ciptaan' => 'Instrumen Diagnostik Penilaian Kemandirian Belajar Siswa Berbasis Web',
                'jenis_ciptaan' => 'Program Komputer',
                'nomor_hki' => 'EC00202589310',
                'pemegang_hak_cipta' => 'IAIN Manado',
                'tahun' => 2025,
                'author_idx' => [1],
            ],
            [
                'judul_ciptaan' => 'Model Algoritma Scoring Kelayakan Pembiayaan Mikro Syariah (Fintech Scoring Engine)',
                'jenis_ciptaan' => 'Program Komputer / Karya Ilmiah',
                'nomor_hki' => 'EC00202591240',
                'pemegang_hak_cipta' => 'IAIN Manado & Muhammad Farid',
                'tahun' => 2025,
                'author_idx' => [2],
            ],
        ];

        foreach ($hakiData as $data) {
            $haki = Haki::create([
                'judul_ciptaan' => $data['judul_ciptaan'],
                'jenis_ciptaan' => $data['jenis_ciptaan'],
                'nomor_hki' => $data['nomor_hki'],
                'pemegang_hak_cipta' => $data['pemegang_hak_cipta'],
                'tahun' => $data['tahun'],
            ]);
            foreach ($data['author_idx'] as $order => $idx) {
                $haki->penciptas()->attach($createdPeneliti[$idx]->id, [
                    'urutan' => $order + 1,
                ]);
            }
        }
    }
}
