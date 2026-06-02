<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Membership;
use App\Models\Reservasi;
use App\Models\Payment;
use App\Models\Paket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class ChatbotController extends Controller
{
    private array $knowledgeBase = [
        'tentang_stc' => 'Satria Training Camp (STC) adalah pusat pelatihan bela diri Muaythai dan Boxing di Purwokerto. Kami berdedikasi membangun kekuatan fisik, mental juara, dan kedisiplinan melalui latihan yang profesional.',
        'lokasi' => 'STC berlokasi di area GOR Satria Purwokerto, Jawa Tengah. Kamu bisa cek lokasi lengkap di halaman utama website kami.',
        'jadwal_regular' => "Jadwal latihan Regular:\n• Selasa & Kamis: Sesi I (16.30-18.00), Sesi II (19.00-20.30)\n• Sabtu & Minggu: Sesi I (15.30-17.00), Sesi II (17.00-18.30)",
        'jadwal_private' => 'Member Private bebas pilih hari dan jam latihan (Senin-Minggu), dengan durasi 1 jam per sesi. Langsung atur sendiri di menu Reservasi.',
        'paket_student' => 'Paket Student (Rp 200.000/bulan): 16 sesi latihan, khusus pelajar/mahasiswa (maks 18 tahun). Regular class dengan fasilitas lengkap.',
        'paket_regular' => 'Paket Regular (Rp 300.000/bulan): 16 sesi latihan, fleksibel pilih jadwal, akses seluruh fasilitas, dan bergabung komunitas positif.',
        'paket_private' => 'Paket Private Class (Rp 550.000/bulan): 8 sesi latihan intensif 1-on-1 dengan Coach. Jadwal bebas, pendampingan khusus, dan hasil lebih cepat.',
        'paket_regular_2bulan' => 'Paket Regular 2 Bulan (Rp 550.000): 32 sesi latihan, lebih hemat!',
        'paket_regular_3bulan' => 'Paket Regular 3 Bulan (Rp 800.000): 48 sesi latihan, paling hemat!',
        'cara_registrasi' => "Cara daftar di STC:\n1. Klik Daftar Sekarang di halaman utama\n2. Isi nama, email, dan password\n3. Login, lalu pilih paket latihan\n4. Bayar via QRIS atau Transfer Bank\n5. Upload bukti pembayaran\n6. Tunggu konfirmasi admin\n7. Setelah disetujui, lengkapi profil dan mulai reservasi!",
        'cara_reservasi' => "Cara reservasi jadwal:\n1. Masuk ke menu Reservasi Jadwal\n2. Pilih hari yang tersedia\n3. Pilih tanggal latihan\n4. Pilih sesi (Regular) / atur jam (Private)\n5. Klik Konfirmasi Reservasi\n\nSesi akan otomatis berkurang setiap kali reservasi.",
        'cara_bayar' => "Cara pembayaran:\n1. Pilih paket di halaman Paket\n2. Pilih metode: QRIS atau Transfer Bank\n3. Lakukan pembayaran sesuai nominal\n4. Upload bukti bayar (foto struk/screenshot)\n5. Klik Selesai & Kirim Konfirmasi\n6. Tunggu admin memverifikasi (biasanya max 1x24 jam)",
        'muaythai' => 'Muaythai (Tinju Thailand) adalah seni bela diri yang menggunakan 8 senjata tubuh: tinju, siku, lutut, dan tendangan. Dikenal sebagai "Art of Eight Limbs". Sangat efektif untuk kebugaran dan self-defense.',
        'boxing' => 'Boxing (tinju) adalah olahraga bela diri yang fokus pada pukulan tangan. Melatih koordinasi, kecepatan, refleks, dan daya tahan kardiovaskular. Cocok untuk pemula dan yang ingin meningkatkan fitness.',
        'manfaat' => "Manfaat latihan di STC:\n• Meningkatkan kebugaran fisik dan stamina\n• Melatih mental dan kedisiplinan\n• Belajar teknik self-defense\n• Mengurangi stres\n• Membentuk tubuh ideal\n• Bergabung dengan komunitas positif",
        'fasilitas' => 'Fasilitas STC meliputi: Ring tinju, heavy bag, speed bag, focus mitt, body protector, area latihan yang luas, dan instruktur profesional berpengalaman.',
    ];

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $user = Auth::user();
        $userQuestion = trim($request->message);
        $systemData = $this->getSystemData();
        $userData = $this->getUserData($user);
        $response = $this->callGemini($systemData, $userData, $userQuestion);

        return $this->respond($response);
    }

    private function getSystemData(): string
    {
        $pakets = Paket::all();
        $paketInfo = "";
        foreach ($pakets as $p) {
            $paketInfo .= "- {$p->nama_paket}: Rp " . number_format($p->harga) . " ({$p->jumlah_sesi} sesi, {$p->durasi_bulan} bulan)\n";
        }

        $data = "INFORMASI STC:\n" . $this->knowledgeBase['tentang_stc'] . "\n\n";
        $data .= "LOKASI:\n" . $this->knowledgeBase['lokasi'] . "\n\n";
        $data .= "DAFTAR PAKET:\n" . $paketInfo . "\n";
        $data .= "JADWAL REGULAR:\n" . $this->knowledgeBase['jadwal_regular'] . "\n";
        $data .= "JADWAL PRIVATE:\n" . $this->knowledgeBase['jadwal_private'] . "\n";
        $data .= "CARA DAFTAR:\n" . $this->knowledgeBase['cara_registrasi'] . "\n";
        $data .= "CARA BAYAR:\n" . $this->knowledgeBase['cara_bayar'] . "\n";
        $data .= "CARA RESERVASI:\n" . $this->knowledgeBase['cara_reservasi'] . "\n";
        $data .= "MANFAAT & FASILITAS:\n" . $this->knowledgeBase['manfaat'] . "\n" . $this->knowledgeBase['fasilitas'];

        return $data;
    }

    private function getUserData($user): string
    {
        $membership = Membership::with('paket')
            ->where('user_id', $user->id)
            ->where('status', 'aktif')
            ->latest()
            ->first();

        $reservasi = Reservasi::where('user_id', $user->id)
            ->where('status', 'booked')
            ->where('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal', 'asc')
            ->take(5)
            ->get();

        $data = "NAMA USER: {$user->name}\n";
        $data .= "EMAIL: {$user->email}\n";
        $data .= "ROLE: {$user->role}\n";

        if ($membership) {
            $data .= "STATUS PAKET: Aktif\n";
            $data .= "NAMA PAKET: " . ($membership->paket->nama_paket ?? 'N/A') . "\n";
            $data .= "SISA SESI: {$membership->sesi_tersisa}\n";
            $data .= "MASA AKTIF SAMPAI: " . Carbon::parse($membership->expired)->translatedFormat('d F Y') . "\n";
        } else {
            $data .= "STATUS PAKET: Tidak memiliki paket aktif / Belum berlangganan\n";
        }

        if ($reservasi->isNotEmpty()) {
            $data .= "JADWAL RESERVASI MENDATANG:\n";
            foreach ($reservasi as $r) {
                $jam = $r->jam_mulai ? Carbon::parse($r->jam_mulai)->format('H:i') . "-" . Carbon::parse($r->jam_selesai)->format('H:i') : $r->sesi;
                $data .= "- {$r->hari}, " . Carbon::parse($r->tanggal)->format('d-m-Y') . " ({$jam})\n";
            }
        } else {
            $data .= "JADWAL RESERVASI: Belum ada reservasi mendatang\n";
        }

        return $data;
    }

    private function callGemini(string $systemData, string $userData, string $userQuestion): string
    {
        $apiKey = config('services.gemini.key');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $promptPath = base_path('PROMPT.md');
        $promptTemplate = File::exists($promptPath) ? File::get($promptPath) : "";

        if (empty($apiKey)) {
            \Log::error("Gemini API Key is missing. Please check your .env and services.php configuration.");
            return "Maaf, Sobat STC sedang tidak bisa menerima tamu (Konfigurasi API belum lengkap).";
        }

        if (empty($promptTemplate)) {
            return "Maaf, sistem sedang mengalami kendala teknis (Prompt not found).";
        }

        $prompt = str_replace(
            ['{{system_data}}', '{{user_data}}', '{{user_question}}'],
            [$systemData, $userData, $userQuestion],
            $promptTemplate
        );

        \Log::info("Gemini Request Sent for user: " . (Auth::user()->email ?? 'unknown'));

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya tidak bisa menjawab itu saat ini 🙏";
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? $response->body();
            \Log::error("Gemini API Error [{$response->status()}]: " . $errorMessage);

            return "Maaf, Sobat STC sedang istirahat sejenak. Coba lagi nanti ya! 😊 (API Error: " . $response->status() . ")";
        } catch (\Exception $e) {
            \Log::error("Gemini Connection Error: " . $e->getMessage());
            return "Waduh, koneksi ke asisten AI terputus. Silakan coba lagi ya!";
        }
    }

    private function getUserMembership($user)
    {
        return Cache::remember("chatbot_membership_{$user->id}", 120, function () use ($user) {
            return Membership::with('paket')
                ->where('user_id', $user->id)
                ->where('status', 'aktif')
                ->latest()
                ->first();
        });
    }

    private function respond(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json(['reply' => $message]);
    }
}
