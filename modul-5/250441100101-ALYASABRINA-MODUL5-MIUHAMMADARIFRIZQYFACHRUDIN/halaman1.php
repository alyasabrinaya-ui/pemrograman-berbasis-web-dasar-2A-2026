<?php
$hasil = "";
$error = "";

function prosesData($frameworkText, $pengalaman, $tools, $bidang, $skill) {
    $framework = array_map("trim", explode(",", $frameworkText));

    $output = "<div class='mt-8 bg-white/10 border border-white/20 p-6 rounded-3xl shadow-2xl'>";
    $output .= "<h2 class='text-2xl font-black mb-4 text-cyan-300'>Hasil Input Developer</h2>";
    $output .= "<div class='overflow-x-auto'>";
    $output .= "<table class='w-full text-left border border-white/20 rounded-xl overflow-hidden'>";
    $output .= "<tr><th class='p-4 bg-white/10 border border-white/20'>Framework</th><td class='p-4 border border-white/20'>" . implode(", ", $framework) . "</td></tr>";
    $output .= "<tr><th class='p-4 bg-white/10 border border-white/20'>Tools</th><td class='p-4 border border-white/20'>" . implode(", ", $tools) . "</td></tr>";
    $output .= "<tr><th class='p-4 bg-white/10 border border-white/20'>Bidang</th><td class='p-4 border border-white/20'>$bidang</td></tr>";
    $output .= "<tr><th class='p-4 bg-white/10 border border-white/20'>Skill</th><td class='p-4 border border-white/20'>$skill</td></tr>";
    $output .= "</table>";
    $output .= "</div>";

    if (count($framework) > 2) {
        $output .= "<p class='mt-4 bg-green-400 text-green-950 p-4 rounded-2xl font-extrabold'>Skill Anda cukup luas di bidang development!</p>";
    }

    $output .= "<p class='mt-4 text-blue-100'><b>Pengalaman:</b> $pengalaman</p>";
    $output .= "</div>";

    return $output;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $framework = $_POST["framework"] ?? "";
    $pengalaman = $_POST["pengalaman"] ?? "";
    $tools = $_POST["tools"] ?? [];
    $bidang = $_POST["bidang"] ?? "";
    $skill = $_POST["skill"] ?? "";

    if ($framework == "" || $pengalaman == "" || empty($tools) || $bidang == "" || $skill == "") {
        $error = "Semua input wajib diisi!";
    } else {
        $hasil = prosesData($framework, $pengalaman, $tools, $bidang, $skill);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Developer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen text-white" style="background: linear-gradient(135deg, #020617, #0f172a, #1e3a8a);">

<nav class="sticky top-0 z-50 bg-slate-950/70 backdrop-blur-xl border-b border-white/10">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center px-6 py-4 gap-4">
        <h1 class="font-black text-2xl text-cyan-300">yaDev</h1>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="halaman1.php" class="px-5 py-2 rounded-full bg-cyan-300 text-slate-950 font-bold">Halaman 1</a>
            <a href="halaman2.php" class="px-5 py-2 rounded-full bg-white/10 hover:bg-cyan-300 hover:text-slate-950 font-bold transition">Halaman 2</a>
            <a href="halaman3.php" class="px-5 py-2 rounded-full bg-white/10 hover:bg-cyan-300 hover:text-slate-950 font-bold transition">Halaman 3</a>
        </div>
    </div>
</nav>

<section class="text-center py-16 px-5">
    <p class="text-cyan-300 font-bold mb-3">HALAMAN 1</p>
    <h1 class="text-4xl md:text-6xl font-black mb-5">Profil Interaktif Developer</h1>
</section>

<main class="w-[90%] max-w-5xl mx-auto bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem] p-8 shadow-2xl mb-16">

    <h2 class="text-3xl font-black text-cyan-300 mb-6">Data Profil</h2>

    <div class="grid md:grid-cols-2 gap-4 mb-10">
        <div class="bg-white/10 p-5 rounded-2xl border border-white/10"><b>Nama:</b> Alya Sabrina</div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/10"><b>ID Developer:</b> 250441100101</div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/10"><b>Kota/Tgl Lahir:</b> Perawang, 11 januari 2007</div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/10"><b>Email:</b> alyasabrinaya@gmail.com</div>
        <div class="bg-white/10 p-5 rounded-2xl border border-white/10"><b>No WhatsApp:</b> 0881081110104</div>
    </div>

    <h2 class="text-3xl font-black text-cyan-300 mb-6">Form Isian Dinamis</h2>

    <?php if ($error): ?>
        <p class="bg-red-400 text-red-950 p-4 rounded-2xl font-extrabold mb-5"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
        <div>
            <label class="font-bold">Framework/Tools yang dikuasai</label>
            <input type="text" name="framework" placeholder="Contoh: HTML, CSS, PHP, Laravel"
                   class="w-full mt-2 p-4 rounded-2xl text-slate-900 outline-none">
        </div>

        <div>
            <label class="font-bold">Pengalaman membuat aplikasi/website</label>
            <textarea name="pengalaman" placeholder="Ceritakan pengalaman singkat..."
                      class="w-full mt-2 p-4 rounded-2xl text-slate-900 outline-none h-28"></textarea>
        </div>

        <div class="bg-white/10 p-5 rounded-2xl">
            <label class="font-bold block mb-3">Tools Penunjang</label>
            <label class="mr-4"><input type="checkbox" name="tools[]" value="VS Code"> VS Code</label>
            <label class="mr-4"><input type="checkbox" name="tools[]" value="GitHub"> GitHub</label>
            <label class="mr-4"><input type="checkbox" name="tools[]" value="Figma"> Figma</label>
            <label class="mr-4"><input type="checkbox" name="tools[]" value="Postman"> Postman</label>
        </div>

        <div class="bg-white/10 p-5 rounded-2xl">
            <label class="font-bold block mb-3">Minat Bidang</label>
            <label class="mr-4"><input type="radio" name="bidang" value="Frontend"> Frontend</label>
            <label class="mr-4"><input type="radio" name="bidang" value="Backend"> Backend</label>
            <label class="mr-4"><input type="radio" name="bidang" value="Fullstack"> Fullstack</label>
        </div>

        <div>
            <label class="font-bold">Tingkat Skill Coding</label>
            <select name="skill" class="w-full mt-2 p-4 rounded-2xl text-slate-900 outline-none">
                <option value="">-- Pilih Skill --</option>
                <option value="Dasar">Dasar</option>
                <option value="Cukup">Cukup</option>
                <option value="Profesional">Profesional</option>
            </select>
        </div>

        <button type="submit" class="bg-cyan-300 text-slate-950 px-8 py-3 rounded-full font-black hover:scale-105 transition">
            Proses Data
        </button>
    </form>

    <?= $hasil; ?>

</main>

</body>
</html>
