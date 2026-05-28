<?php
$timeline = [
    ["tahun" => "2025", "judul" => "Mulai Mengenal Coding", "isi" => "Belajar dasar logika pemrograman dan memahami cara kerja website.", "icon" => "💡", "warna" => "from-blue-500 to-cyan-400"],
    ["tahun" => "2025", "judul" => "Belajar python ", "isi" => "Belajar penggunaan python, logika dasar python.", "icon" => "🚀", "warna" => "from-emerald-400 to-teal-500"],
    ["tahun" => "2026", "judul" => "Belajar Frontend", "isi" => "Mempelajari HTML, CSS, Tailwind, dan membuat tampilan website responsive.", "icon" => "🎨", "warna" => "from-pink-500 to-rose-400"],
    ["tahun" => "2026", "judul" => "Belajar JavaScript", "isi" => "Mulai membuat website interaktif dengan event, DOM, dan animasi sederhana.", "icon" => "⚡", "warna" => "from-yellow-400 to-orange-500"],
    ["tahun" => "2026", "judul" => "Belajar PHP", "isi" => "Membuat halaman dinamis dengan form, GET, POST, array, dan function.", "icon" => "🐘", "warna" => "from-indigo-500 to-purple-500"]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Timeline Developer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen text-white overflow-x-hidden" style="background: linear-gradient(135deg, #020617, #111827, #1e3a8a);">

<nav class="sticky top-0 z-50 bg-slate-950/70 backdrop-blur-xl border-b border-white/10">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center px-6 py-4 gap-4">
        <h1 class="font-black text-2xl text-cyan-300">DevJourney</h1>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="index.php" class="px-5 py-2 rounded-full bg-white/10 hover:bg-cyan-300 hover:text-slate-950 font-bold transition">Halaman 1</a>
            <a href="timeline.php" class="px-5 py-2 rounded-full bg-cyan-300 text-slate-950 font-bold">Halaman 2</a>
            <a href="blog.php" class="px-5 py-2 rounded-full bg-white/10 hover:bg-cyan-300 hover:text-slate-950 font-bold transition">Halaman 3</a>
        </div>
    </div>
</nav>

<section class="relative text-center py-20 px-5">
    <div class="absolute top-10 left-10 w-32 h-32 bg-cyan-400 rounded-full blur-3xl opacity-30"></div>
    <div class="absolute bottom-10 right-10 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-30"></div>

    <p class="text-cyan-300 font-bold mb-3">HALAMAN 2</p>
    <h1 class="text-5xl md:text-7xl font-black mb-5">
        Timeline Belajar <span class="text-cyan-300">Coding</span>
    </h1>
    <p class="text-slate-300 max-w-2xl mx-auto text-lg">
        Perjalanan belajar dari dasar sampai mulai membuat project website sendiri.
    </p>
</section>

<main class="max-w-6xl mx-auto px-6 pb-20">
    <div class="grid md:grid-cols-5 gap-6">
        <?php foreach ($timeline as $data): ?>
            <div class="group relative rounded-3xl p-[2px] bg-gradient-to-br <?= $data["warna"]; ?> hover:scale-105 transition duration-300">
                <div class="h-full rounded-3xl bg-slate-950/90 p-6 border border-white/10">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br <?= $data["warna"]; ?> flex items-center justify-center text-3xl mb-5 shadow-lg">
                        <?= $data["icon"]; ?>
                    </div>

                    <p class="text-4xl font-black text-white mb-2"><?= $data["tahun"]; ?></p>
                    <h2 class="text-xl font-extrabold mb-3 text-cyan-200"><?= $data["judul"]; ?></h2>
                    <p class="text-slate-300 leading-relaxed text-sm"><?= $data["isi"]; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-14 rounded-3xl p-8 bg-white/10 border border-white/10 backdrop-blur-xl text-center">
        <h2 class="text-3xl font-black mb-3">Target Selanjutnya</h2>
        <p class="text-slate-300 mb-6">
            Terus meningkatkan kemampuan coding dengan membuat project nyata dan belajar database.
        </p>

        <a href="halaman1.php" class="inline-block m-2 px-7 py-3 rounded-full bg-white text-slate-950 font-extrabold hover:scale-105 transition">
            Ke Halaman 1
        </a>
        <a href="halaman3.php" class="inline-block m-2 px-7 py-3 rounded-full bg-cyan-300 text-slate-950 font-extrabold hover:scale-105 transition">
            Ke Halaman 3
        </a>
    </div>
</main>

</body>
</html>
