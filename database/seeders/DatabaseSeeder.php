<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\SkemaBantuan;
use App\Models\Peneliti;
use App\Models\Penelitian;
use App\Models\Buku;
use App\Models\Pkm;
use App\Models\Haki;
use App\Models\Publikasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@apik.local'],
            [
                'name' => 'Super Administrator APIK',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Fakultas
        $fst = Fakultas::create(['nama' => 'Fakultas Sains dan Teknologi', 'kode' => 'FST']);
        $febi = Fakultas::create(['nama' => 'Fakultas Ekonomi dan Bisnis Islam', 'kode' => 'FEBI']);
        $ftk = Fakultas::create(['nama' => 'Fakultas Tarbiyah dan Keguruan', 'kode' => 'FTK']);
        $fsh = Fakultas::create(['nama' => 'Fakultas Syariah dan Hukum', 'kode' => 'FSH']);
        $fkik = Fakultas::create(['nama' => 'Fakultas Kedokteran dan Ilmu Kesehatan', 'kode' => 'FKIK']);

        // 3. Prodi
        $ti = Prodi::create(['fakultas_id' => $fst->id, 'nama' => 'Teknik Informatika', 'jenjang' => 'S1', 'kode' => 'TI']);
        $si = Prodi::create(['fakultas_id' => $fst->id, 'nama' => 'Sistem Informasi', 'jenjang' => 'S1', 'kode' => 'SI']);
        $bio = Prodi::create(['fakultas_id' => $fst->id, 'nama' => 'Biologi', 'jenjang' => 'S1', 'kode' => 'BIO']);
        $mgt = Prodi::create(['fakultas_id' => $febi->id, 'nama' => 'Manajemen', 'jenjang' => 'S1', 'kode' => 'MGT']);
        $far = Prodi::create(['fakultas_id' => $fkik->id, 'nama' => 'Farmasi', 'jenjang' => 'S1', 'kode' => 'FAR']);

        // 4. Skema Bantuan
        $skema1 = SkemaBantuan::create([
            'nama' => 'Bantuan Penelitian Pembinaan/Kapasitas',
            'jenis' => 'penelitian',
            'keterangan' => 'Hibah penelitian pemula bagi dosen muda'
        ]);
        $skema2 = SkemaBantuan::create([
            'nama' => 'Bantuan Penelitian Terapan & Pengembangan',
            'jenis' => 'penelitian',
            'keterangan' => 'Hibah penelitian luaran prototipe/paten'
        ]);
        $skema3 = SkemaBantuan::create([
            'nama' => 'Bantuan PKM Berbasis Program Studi',
            'jenis' => 'pkm',
            'keterangan' => 'Pengabdian masyarakat tematik prodi'
        ]);
        $skema4 = SkemaBantuan::create([
            'nama' => 'Bantuan PKM Kolaborasi Desa Binaan',
            'jenis' => 'pkm',
            'keterangan' => 'Pemberdayaan masyarakat pedesaan berkelanjutan'
        ]);

        // 5. Peneliti
        $p1 = Peneliti::create([
            'nama_lengkap' => 'Prof. Dr. H. Faisal Ardiansyah, M.T.',
            'nidn' => '2015088201',
            'prodi_id' => $ti->id,
            'email' => 'faisal.ardiansyah@institusi.ac.id',
            'no_hp' => '081234567890',
            'bidang_keahlian' => 'Kecerdasan Buatan, Data Science, Image Processing',
            'sinta_id' => '6012481',
            'scopus_id' => '57201948123',
            'gscholar_id' => 'abCD1234EFG',
            'orcid_id' => '0000-0002-1825-0097',
            'status' => 'aktif',
        ]);

        $p2 = Peneliti::create([
            'nama_lengkap' => 'Dr. Nurul Hikmah, M.Kom.',
            'nidn' => '2022048502',
            'prodi_id' => $si->id,
            'email' => 'nurul.hikmah@institusi.ac.id',
            'no_hp' => '081234567891',
            'bidang_keahlian' => 'Enterprise Architecture, UI/UX Design, IT Governance',
            'sinta_id' => '6019284',
            'scopus_id' => '57218392019',
            'gscholar_id' => 'ghIJ5678KLM',
            'orcid_id' => '0000-0003-4921-1182',
            'status' => 'aktif',
        ]);

        $p3 = Peneliti::create([
            'nama_lengkap' => 'Ahmad Fauzi, S.Si., M.Sc., Ph.D.',
            'nidn' => '2019038903',
            'prodi_id' => $bio->id,
            'email' => 'ahmad.fauzi@institusi.ac.id',
            'no_hp' => '081234567892',
            'bidang_keahlian' => 'Bioteknologi Molekuler, Mikrobiologi Terapan',
            'sinta_id' => '6038192',
            'scopus_id' => '57291823901',
            'gscholar_id' => 'noPQ9012RST',
            'status' => 'aktif',
        ]);

        $p4 = Peneliti::create([
            'nama_lengkap' => 'Siti Rahmawati, S.E., M.Si.',
            'nidn' => '2018078604',
            'prodi_id' => $mgt->id,
            'email' => 'siti.rahma@institusi.ac.id',
            'no_hp' => '081234567893',
            'bidang_keahlian' => 'Manajemen Keuangan Syariah, Digital Fintech',
            'sinta_id' => '6048190',
            'status' => 'aktif',
        ]);

        // 6. Penelitian
        $pen1 = Penelitian::create([
            'nomor' => 'SK-LP2M/2026/014',
            'skema_bantuan_id' => $skema2->id,
            'judul' => 'Pengembangan Sistem Pengenalan Pola Citra Medis Berbasis Deep Learning untuk Deteksi Dini Kanker',
            'tahun' => 2026,
            'tautan_tagihan' => 'https://drive.google.com/sample-bukti-pencairan-1',
        ]);
        $pen1->penelitis()->attach([
            $p1->id => ['urutan' => 1, 'peran' => 'Ketua Peneliti'],
            $p2->id => ['urutan' => 2, 'peran' => 'Anggota Peneliti'],
        ]);

        $pen2 = Penelitian::create([
            'nomor' => 'SK-LP2M/2025/088',
            'skema_bantuan_id' => $skema1->id,
            'judul' => 'Analisis Ketahanan Ekstrak Daun Kelor terhadap Bakteri Patogen Resisten Multi-Obat',
            'tahun' => 2025,
            'tautan_tagihan' => 'https://drive.google.com/sample-bukti-pencairan-2',
        ]);
        $pen2->penelitis()->attach([
            $p3->id => ['urutan' => 1, 'peran' => 'Ketua Peneliti'],
        ]);

        // 7. Buku
        $buku1 = Buku::create([
            'nomor' => 'BK-2025-01',
            'nama_penerbit' => 'Deepublish Press Indonesia',
            'judul' => 'Dasar-Dasar Kecerdasan Artifisial: Konsep, Teori, dan Implementasi Python',
            'tahun' => 2025,
            'isbn' => '978-623-02-8192-1',
            'tautan' => 'https://penerbitbukudeepublish.com',
        ]);
        $buku1->penuliss()->attach([
            $p1->id => ['urutan' => 1],
            $p2->id => ['urutan' => 2],
        ]);

        $buku2 = Buku::create([
            'nomor' => 'BK-2024-05',
            'nama_penerbit' => 'Prenadamedia Group',
            'judul' => 'Manajemen Keuangan Syariah di Era Disrupsi Digital',
            'tahun' => 2024,
            'isbn' => '978-602-422-901-4',
        ]);
        $buku2->penuliss()->attach([
            $p4->id => ['urutan' => 1],
        ]);

        // 8. PKM
        $pkm1 = Pkm::create([
            'nomor' => 'PKM-2025/022',
            'skema_bantuan_id' => $skema3->id,
            'judul' => 'Pelatihan Transformasi Digital & Pemanfaatan E-Commerce untuk UMKM Pengrajin Lokal',
            'tahun' => 2025,
            'tautan_tagihan' => 'https://drive.google.com/sample-pkm-1',
        ]);
        $pkm1->pengabdis()->attach([
            $p2->id => ['urutan' => 1, 'peran' => 'Ketua Pengabdi'],
            $p4->id => ['urutan' => 2, 'peran' => 'Anggota Pengabdi'],
        ]);

        // 9. HKI / Paten
        $haki1 = Haki::create([
            'nomor_hki' => 'EC00202519284',
            'pemegang_hak_cipta' => 'LP2M Institusi',
            'jenis_ciptaan' => 'Program Komputer',
            'judul_ciptaan' => 'Aplikasi Web Telemedisin Berbasis AI untuk Triase Pasien',
            'tahun' => 2025,
            'tautan_tagihan' => 'https://e-hakcipta.dgip.go.id/check/EC00202519284',
        ]);
        $haki1->penciptas()->attach([
            $p1->id => ['urutan' => 1],
            $p2->id => ['urutan' => 2],
        ]);

        // 10. Publikasi
        $pub1 = Publikasi::create([
            'jenis_publikasi' => 'jurnal',
            'kategori_indeks' => 'Scopus Q1',
            'judul' => 'Deep Transfer Learning Architecture for Real-time Microscopic Biological Cell Classification',
            'tahun' => 2026,
            'nama_jurnal_prosiding' => 'IEEE Transactions on Medical Imaging',
            'tautan_doi' => 'https://doi.org/10.1109/TMI.2026.1049281',
        ]);
        $pub1->penuliss()->attach([
            $p1->id => ['urutan' => 1, 'peran' => 'Penulis Pertama'],
            $p3->id => ['urutan' => 2, 'peran' => 'Penulis Korespondensi'],
        ]);

        $pub2 = Publikasi::create([
            'jenis_publikasi' => 'jurnal',
            'kategori_indeks' => 'SINTA 2',
            'judul' => 'Evaluasi Usability dan User Acceptance Sistem Informasi Akademik Berbasis Model UTAUT',
            'tahun' => 2025,
            'nama_jurnal_prosiding' => 'Jurnal Sistem Informasi dan Teknologi (JUSTIN)',
            'tautan_doi' => 'https://doi.org/10.26418/justin.v13i2.67891',
        ]);
        $pub2->penuliss()->attach([
            $p2->id => ['urutan' => 1, 'peran' => 'Penulis Pertama'],
        ]);

        $pub3 = Publikasi::create([
            'jenis_publikasi' => 'prosiding',
            'kategori_indeks' => 'Scopus Indexed',
            'judul' => 'Financial Literacy and Mobile Banking Adoption among Gen-Z in Emerging Markets',
            'tahun' => 2025,
            'nama_jurnal_prosiding' => 'AIP Conference Proceedings 2025',
            'tautan_doi' => 'https://doi.org/10.1063/5.0192841',
        ]);
        $pub3->penuliss()->attach([
            $p4->id => ['urutan' => 1, 'peran' => 'Penulis Pertama'],
        ]);
    }
}
