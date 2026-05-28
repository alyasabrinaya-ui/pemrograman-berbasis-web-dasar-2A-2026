<?php
$artikel = [
    "html" => [
        "kategori" => "Frontend",
        "judul" => "Belajar HTML Pertama Kali",
        "tanggal" => "10 Januari 2025",
        "isi" => "HTML adalah awal perjalanan saya dalam dunia web development. Dari HTML saya belajar membuat struktur halaman seperti judul, paragraf, link, gambar, dan tabel.",
        "gambar" => "html.webp"
    ],
    "error" => [
        "kategori" => "Experience",
        "judul" => "Error Pertama Saat Coding",
        "tanggal" => "15 Februari 2025",
        "isi" => "Error pertama membuat saya bingung, tetapi dari situ saya belajar bahwa coding bukan hanya menulis kode, tetapi juga membaca pesan error dan mencari solusi.",
        "gambar" => "error.webp"
    ],
    "php" => [
        "kategori" => "Backend",
        "judul" => "Mulai Mengenal PHP",
        "tanggal" => "20 Maret 2025",
        "isi" => "PHP membuat saya memahami bagaimana data dari form dapat diproses. Dengan PHP, website menjadi lebih dinamis dan interaktif.",
        "gambar" => "https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200"
    ]
];

$quotes = [
    "Error bukan kegagalan, tapi petunjuk untuk belajar lebih dalam.",
    "Developer hebat terbentuk dari latihan yang konsisten.",
    "Satu project kecil lebih baik daripada seribu rencana besar.",
    "Semakin sering mencoba, semakin dekat dengan bisa."
];

$pilihan = $_GET["artikel"] ?? "html";
$data = $artikel[$pilihan] ?? $artikel["html"];
$quote = $quotes[array_rand($quotes)];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Blog Developer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen text-white" style="background: radial-gradient(circle at top left, #2563eb, transparent 30%), linear-gradient(135deg, #020617, #0f172a, #111827);">

<nav class="sticky top-0 z-50 bg-slate-950/70 backdrop-blur-xl border-b border-white/10">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center px-6 py-4 gap-4">
        <h1 class="font-black text-2xl text-fuchsia-300">DevBlog</h1>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="index.php" class="px-5 py-2 rounded-full bg-white/10 hover:bg-fuchsia-300 hover:text-slate-950 font-bold transition">Halaman 1</a>
            <a href="timeline.php" class="px-5 py-2 rounded-full bg-white/10 hover:bg-fuchsia-300 hover:text-slate-950 font-bold transition">Halaman 2</a>
            <a href="blog.php" class="px-5 py-2 rounded-full bg-fuchsia-300 text-slate-950 font-bold">Halaman 3</a>
        </div>
    </div>
</nav>

<section class="max-w-6xl mx-auto px-6 py-16">
    <div class="grid md:grid-cols-2 gap-10 items-center">
        <div>
            <p class="text-fuchsia-300 font-bold mb-3">HALAMAN 3</p>
            <h1 class="text-5xl md:text-7xl font-black mb-5">
                Catatan Belajar <span class="text-fuchsia-300">Developer</span>
            </h1>
            <p class="text-slate-300 text-lg leading-relaxed">
                Berisi pengalaman belajar coding, menghadapi error, dan proses menjadi developer pemula.
            </p>
        </div>

        <div class="rounded-[2rem] p-3 bg-white/10 border border-white/10 backdrop-blur-xl">
            <img src="<?= $data["gambar"]; ?>" 
                 class="w-full h-[360px] object-cover rounded-[1.5rem] shadow-2xl"
                 alt="Gambar Artikel">
        </div>
    </div>
</section>

<main class="max-w-6xl mx-auto px-6 pb-20">
    <div class="flex flex-wrap gap-4 mb-10">
        <a href="blog.php?artikel=html" class="px-6 py-3 rounded-full bg-blue-500 hover:bg-blue-400 font-bold transition">HTML</a>
        <a href="blog.php?artikel=error" class="px-6 py-3 rounded-full bg-orange-500 hover:bg-orange-400 font-bold transition">Error</a>
        <a href="blog.php?artikel=php" class="px-6 py-3 rounded-full bg-purple-500 hover:bg-purple-400 font-bold transition">PHP</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <article class="lg:col-span-2 rounded-3xl p-8 bg-white/10 border border-white/10 backdrop-blur-xl shadow-2xl">
            <span class="inline-block px-4 py-2 bg-fuchsia-300 text-slate-950 rounded-full font-extrabold mb-5">
                <?= $data["kategori"]; ?>
            </span>

            <h2 class="text-4xl font-black mb-3"><?= $data["judul"]; ?></h2>

            <p class="text-cyan-300 font-bold mb-6">
                Diposting pada <?= $data["tanggal"]; ?>
            </p>

            <p class="text-slate-200 text-lg leading-9 mb-8">
                <?= $data["isi"]; ?>
            </p>

            <blockquote class="rounded-3xl p-6 bg-gradient-to-r from-fuchsia-500/20 to-cyan-500/20 border border-white/10">
                <p class="text-xl italic text-fuchsia-100">“<?= $quote; ?>”</p>
            </blockquote>
        </article>

        <aside class="rounded-3xl p-8 bg-slate-950/70 border border-white/10 shadow-2xl">
            <h3 class="text-2xl font-black mb-5 text-fuchsia-300">Info Artikel</h3>

            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-white/10">
                    <p class="text-slate-400">Kategori</p>
                    <p class="font-bold"><?= $data["kategori"]; ?></p>
                </div>

                <div class="p-4 rounded-2xl bg-white/10">
                    <p class="text-slate-400">Tanggal</p>
                    <p class="font-bold"><?= $data["tanggal"]; ?></p>
                </div>

                <div class="p-4 rounded-2xl bg-white/10">
                    <p class="text-slate-400">Referensi</p>
                    <a href="https://www.php.net" target="_blank" class="text-cyan-300 font-bold underline">
                        Dokumentasi PHP
                    </a>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3">
                <a href="halaman1.php" class="block text-center px-6 py-3 rounded-full bg-white text-slate-950 font-extrabold hover:scale-105 transition">
                    Ke Halaman 1
                </a>

                <a href="halama2.php" class="block text-center px-6 py-3 rounded-full bg-fuchsia-300 text-slate-950 font-extrabold hover:scale-105 transition">
                    Ke Halaman 2
                </a>
            </div>
        </aside>
    </div>
</main>

</body>
</html>
