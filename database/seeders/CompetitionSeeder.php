<?php

namespace Database\Seeders;

use App\Models\Competition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $competitions = [

            // ─── 1. Fun Run 5K ───────────────────────────────────────────
            [
                'name'            => 'Fun Run 5K',
                'slug'            => 'fun-run-5k',
                'event_name'      => 'GEN FEST 2026',
                'category'        => 'Olahraga',
                'unit'            => 'peserta',
                'price'           => 235_000,          // harga normal
                'price_community' => 175_000,          // diskon komunitas
                'price_early_bird'=> 225_000,          // early bird
                'quota'           => 1000,
                'event_date'      => '2026-09-20',
                'location'        => 'Batam',
                'status'          => 'open',
                'description'     =>
                    "Fun Run 5K adalah ajang lari santai yang terbuka untuk komunitas lari, pelajar, mahasiswa, dan masyarakat umum. " .
                    "Nikmati rute yang menyenangkan bersama ribuan peserta lainnya.\n\n" .
                    "Tersedia tiga kategori harga:\n" .
                    "• Harga Normal     : Rp235.000/peserta\n" .
                    "• Early Bird       : Rp225.000/peserta\n" .
                    "• Harga Komunitas  : Rp175.000/peserta",
                'requirements'    =>
                    "- Terbuka untuk komunitas lari, pelajar, mahasiswa, dan masyarakat umum\n" .
                    "- Usia peserta 12–50 tahun\n" .
                    "- Peserta wajib dalam kondisi sehat\n" .
                    "- Membawa e-ticket saat hari pelaksanaan\n" .
                    "- Diskon komunitas berlaku untuk pendaftar yang menyertakan bukti keanggotaan komunitas lari",
                'rules'           =>
                    "1. Peserta wajib hadir 30 menit sebelum start.\n" .
                    "2. Dilarang menggunakan kendaraan bermotor di jalur lari.\n" .
                    "3. Peserta wajib mengenakan bib nomor yang diberikan panitia.\n" .
                    "4. Panitia tidak bertanggung jawab atas cedera yang disebabkan kelalaian peserta.\n" .
                    "5. Keputusan panitia bersifat final.",
            ],

            // ─── 2. Paskib Competition ────────────────────────────────────
            [
                'name'            => 'Paskib Competition',
                'slug'            => 'paskib-competition',
                'event_name'      => 'GEN FEST 2026',
                'category'        => 'Akademik',
                'unit'            => 'peserta',
                'price'           => 30_000,
                'price_community' => null,
                'price_early_bird'=> null,
                'quota'           => 1500,
                'event_date'      => '2026-09-20',
                'location'        => 'Batam',
                'status'          => 'open',
                'description'     =>
                    "Kompetisi Paskibra antar pelajar tingkat SMA/SMK/MA se-Kota Batam & Kepulauan Riau. " .
                    "Uji kemampuan baris-berbaris dan kedisiplinan tim kamu di ajang bergengsi ini.",
                'requirements'    =>
                    "- Khusus pelajar aktif SMA/SMK/MA sederajat se-Kota Batam & Kepri\n" .
                    "- Usia peserta 16–20 tahun\n" .
                    "- Membawa kartu pelajar/surat keterangan aktif\n" .
                    "- Seragam Paskibra lengkap dan rapi\n" .
                    "- Membawa e-ticket saat hari pelaksanaan",
                'rules'           =>
                    "1. Setiap regu terdiri dari jumlah peserta sesuai ketentuan panitia.\n" .
                    "2. Peserta wajib hadir 45 menit sebelum penampilan.\n" .
                    "3. Penilaian meliputi kerapian, kekompakan, ketepatan gerakan, dan penampilan.\n" .
                    "4. Keputusan juri bersifat final dan tidak dapat diganggu gugat.",
            ],

            // ─── 3. Dance Competition ────────────────────────────────────
            [
                'name'            => 'Dance Competition (Modern Dance / K-Pop Cover)',
                'slug'            => 'dance-competition',
                'event_name'      => 'GEN FEST 2026',
                'category'        => 'Seni & Musik',
                'unit'            => 'team',
                'price'           => 250_000,
                'price_community' => null,
                'price_early_bird'=> null,
                'quota'           => 15,
                'event_date'      => '2026-09-20',
                'location'        => 'Batam',
                'status'          => 'open',
                'description'     =>
                    "Tunjukkan bakat seni tari modern dan K-Pop cover terbaikmu! Dance Competition terbuka untuk pelajar SMA yang ingin unjuk gigi di atas panggung GEN FEST 2026. " .
                    "Kategori: Modern Dance & K-Pop Cover.",
                'requirements'    =>
                    "- Pelajar aktif SMA/MA/SMK sederajat\n" .
                    "- Usia peserta 15–18 tahun\n" .
                    "- Setiap team minimal 3 orang, maksimal 10 orang\n" .
                    "- Membawa kartu pelajar\n" .
                    "- Kostum sesuai konsep penampilan (sopan dan tidak melanggar norma)",
                'rules'           =>
                    "1. Durasi penampilan 3–7 menit termasuk persiapan.\n" .
                    "2. Musik diserahkan dalam format MP3 ke panitia H-1 pelaksanaan.\n" .
                    "3. Dilarang menggunakan properti berbahaya.\n" .
                    "4. Penilaian: koreografi, keselarasan, kostum, ekspresi, dan teknik.\n" .
                    "5. Keputusan juri bersifat final.",
            ],

            // ─── 4. Lomba Tari Tradisional ───────────────────────────────
            [
                'name'            => 'Lomba Tari Tradisional',
                'slug'            => 'lomba-tari-tradisional',
                'event_name'      => 'GEN FEST 2026',
                'category'        => 'Seni & Musik',
                'unit'            => 'team',
                'price'           => 250_000,
                'price_community' => null,
                'price_early_bird'=> null,
                'quota'           => 15,
                'event_date'      => '2026-09-20',
                'location'        => 'Batam',
                'status'          => 'open',
                'description'     =>
                    "Lestarikan budaya Indonesia melalui panggung GEN FEST 2026! Lomba Tari Tradisional terbuka untuk pelajar SMA yang ingin menampilkan keindahan tari daerah Nusantara.",
                'requirements'    =>
                    "- Pelajar aktif SMA/MA/SMK sederajat\n" .
                    "- Usia peserta 15–18 tahun\n" .
                    "- Setiap team minimal 3 orang, maksimal 10 orang\n" .
                    "- Membawa kartu pelajar\n" .
                    "- Kostum tari tradisional sesuai daerah yang dibawakan",
                'rules'           =>
                    "1. Durasi penampilan 5–10 menit termasuk persiapan.\n" .
                    "2. Tarian yang dibawakan merupakan tari tradisional daerah Indonesia.\n" .
                    "3. Iringan musik diserahkan dalam format MP3 ke panitia H-1.\n" .
                    "4. Penilaian: keaslian gerakan, kostum, kekompakan, dan penghayatan.\n" .
                    "5. Keputusan juri bersifat final.",
            ],

            // ─── 5. Band Competition ─────────────────────────────────────
            [
                'name'            => 'Band Competition',
                'slug'            => 'band-competition',
                'event_name'      => 'GEN FEST 2026',
                'category'        => 'Seni & Musik',
                'unit'            => 'team',
                'price'           => 300_000,
                'price_community' => null,
                'price_early_bird'=> null,
                'quota'           => 20,
                'event_date'      => '2026-09-20',
                'location'        => 'Batam',
                'status'          => 'open',
                'description'     =>
                    "Unjuk kemampuan musikal terbaikmu di atas panggung GEN FEST 2026! Band Competition terbuka untuk band pelajar SMA se-Kota Batam. " .
                    "Tunjukkan kreativitas dan permainan musik terbaikmu.",
                'requirements'    =>
                    "- Pelajar aktif SMA/SMK sederajat se-Kota Batam\n" .
                    "- Usia anggota 16–20 tahun\n" .
                    "- Setiap band terdiri dari minimal 3 orang, maksimal 7 orang\n" .
                    "- Membawa kartu pelajar masing-masing anggota\n" .
                    "- Peralatan band standar disediakan panitia (drum, ampli gitar, ampli bass, keyboard)",
                'rules'           =>
                    "1. Setiap band membawakan 2 lagu: 1 lagu wajib (ditentukan panitia) dan 1 lagu bebas.\n" .
                    "2. Durasi penampilan maksimal 15 menit.\n" .
                    "3. Dilarang membawakan lagu dengan lirik berbau SARA atau tidak senonoh.\n" .
                    "4. Sound check dilakukan H-1 pelaksanaan.\n" .
                    "5. Penilaian: teknik, aransemen, kekompakan, penampilan, dan penguasaan panggung.\n" .
                    "6. Keputusan juri bersifat final.",
            ],

            // ─── 6. Lomba Mewarnai & Fashion Show ────────────────────────
            [
                'name'            => 'Lomba Mewarnai & Fashion Show',
                'slug'            => 'lomba-mewarnai-fashion-show',
                'event_name'      => 'GEN FEST 2026',
                'category'        => 'Kreatif',
                'unit'            => 'peserta',
                'price'           => 25_000,
                'price_community' => null,
                'price_early_bird'=> null,
                'quota'           => 60,
                'event_date'      => '2026-09-20',
                'location'        => 'Batam',
                'status'          => 'open',
                'description'     =>
                    "Ajang kreatif untuk anak-anak usia 4–9 tahun (TK & SD Kelas 1–3). " .
                    "Dua kategori dalam satu event: Lomba Mewarnai dan Fashion Show. " .
                    "Biarkan si kecil unjuk bakat dan kreativitasnya!",
                'requirements'    =>
                    "- Anak-anak usia 4–9 tahun\n" .
                    "- Tingkat TK dan SD Kelas 1–3\n" .
                    "- Didampingi orang tua/wali saat pelaksanaan\n" .
                    "- Lomba Mewarnai: membawa krayon/pensil warna sendiri\n" .
                    "- Fashion Show: kostum unik/kreatif disiapkan sendiri oleh peserta",
                'rules'           =>
                    "1. Lomba Mewarnai: gambar disediakan panitia, peserta menggunakan alat mewarnai sendiri.\n" .
                    "2. Fashion Show: peserta memperagakan kostum di atas catwalk yang disediakan.\n" .
                    "3. Orang tua dilarang masuk ke area lomba mewarnai saat berlangsung.\n" .
                    "4. Penilaian Mewarnai: kerapian, kreativitas warna, dan kebersihan.\n" .
                    "5. Penilaian Fashion Show: kostum, kepercayaan diri, dan ekspresi.\n" .
                    "6. Keputusan juri bersifat final.",
            ],
        ];

        $count = 0;
        foreach ($competitions as $data) {
            Competition::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
            $count++;
        }

        $this->command->info("✓ {$count} lomba berhasil ditambahkan/diperbarui.");

        // Tampilkan ringkasan
        $this->command->table(
            ['No', 'Nama Lomba', 'Kategori', 'Harga Normal', 'Kuota', 'Unit'],
            collect($competitions)->map(fn ($c, $i) => [
                $i + 1,
                $c['name'],
                $c['category'],
                'Rp' . number_format($c['price'], 0, ',', '.'),
                number_format($c['quota']),
                $c['unit'],
            ])->toArray()
        );
    }
}
