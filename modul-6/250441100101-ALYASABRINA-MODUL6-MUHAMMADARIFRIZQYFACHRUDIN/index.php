<?php
session_start();


$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'db_modul6_perpustakaan';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die('Koneksi database gagal. Pastikan sudah import database.sql dan MySQL aktif. Error: ' . htmlspecialchars($conn->connect_error));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function is_login() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect_to($url) {
    header('Location: ' . $url);
    exit;
}

function require_login() {
    if (!is_login()) {
        redirect_to('?page=login');
    }
}   

$page = $_GET['page'] ?? (is_login() ? 'dashboard' : 'login');
$errors = [];
$success = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($username) < 4) $errors[] = 'Username minimal 4 karakter.';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';    
        $stmt = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $username, $hash, $role);
        if ($stmt->execute()) {
            $success = 'Registrasi berhasil. Silakan login.';
            $page = 'login';
        } else {
            $errors[] = 'Username sudah digunakan.';
        }
        $stmt->close();
    }
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        redirect_to('?page=dashboard');
    } else {
        $errors[] = 'Username atau password salah.';
        $page = 'login';
    }
}


if ($page === 'logout') {
    session_destroy();
    redirect_to('?page=login');
}


if (isset($_POST['create_buku'])) {
    require_login();
    $judul = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $tahun = (int)($_POST['tahun_terbit'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);
    $harga = (float)($_POST['harga'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($judul === '' || $penulis === '' || $kategori === '') $errors[] = 'Judul, penulis, dan kategori wajib diisi.';
    if ($tahun < 1900 || $tahun > (int)date('Y')) $errors[] = 'Tahun terbit tidak valid.';
    if ($stok < 0 || $harga < 0) $errors[] = 'Stok dan harga tidak boleh negatif.';

    if (!$errors) {
        $stmt = $conn->prepare('INSERT INTO buku (judul, penulis, kategori, tahun_terbit, stok, harga, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssiids', $judul, $penulis, $kategori, $tahun, $stok, $harga, $deskripsi);
        $stmt->execute();
        $stmt->close();
        redirect_to('?page=dashboard&msg=created');
    }
    $page = 'form';
}

if (isset($_POST['update_buku'])) {
    require_login();
    $id = (int)($_POST['id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $tahun = (int)($_POST['tahun_terbit'] ?? 0);
    $stok = (int)($_POST['stok'] ?? 0);
    $harga = (float)($_POST['harga'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($id <= 0) $errors[] = 'ID buku tidak valid.';
    if ($judul === '' || $penulis === '' || $kategori === '') $errors[] = 'Judul, penulis, dan kategori wajib diisi.';
    if ($tahun < 1900 || $tahun > (int)date('Y')) $errors[] = 'Tahun terbit tidak valid.';
    if ($stok < 0 || $harga < 0) $errors[] = 'Stok dan harga tidak boleh negatif.';

    if (!$errors) {
        $stmt = $conn->prepare('UPDATE buku SET judul=?, penulis=?, kategori=?, tahun_terbit=?, stok=?, harga=?, deskripsi=? WHERE id=?');
        $stmt->bind_param('sssiidsi', $judul, $penulis, $kategori, $tahun, $stok, $harga, $deskripsi, $id);
        $stmt->execute();
        $stmt->close();
        redirect_to('?page=dashboard&msg=updated');
    }
    $page = 'form';
}


if ($page === 'delete') {
    require_login();
    if (!is_admin()) {
        die('Akses ditolak. Hanya admin yang boleh menghapus data.');
    }
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare('DELETE FROM buku WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
    redirect_to('?page=dashboard&msg=deleted');
}

if (isset($_GET['msg'])) {
    $messages = [
        'created' => 'Data buku berhasil ditambahkan.',
        'updated' => 'Data buku berhasil diperbarui.',
        'deleted' => 'Data buku berhasil dihapus.'
    ];
    $success = $messages[$_GET['msg']] ?? '';
}

$editData = null;
if ($page === 'form' && isset($_GET['id'])) {
    require_login();
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM buku WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$editData) redirect_to('?page=dashboard');
}

if (in_array($page, ['dashboard','form'], true)) require_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Praktikum Modul 6 - Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800" style="font-family: Arial, sans-serif;">
<nav class="bg-indigo-700 text-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="?page=dashboard" class="font-bold text-xl">📚 Perpustakaan Modul 6</a>
        <div class="flex gap-3 items-center text-sm">
            <?php if (is_login()): ?>
                <span class="hidden sm:inline">Login: <b><?= e($_SESSION['username']) ?></b> (<?= e($_SESSION['role']) ?>)</span>
                <a href="?page=dashboard" class="hover:underline">Data Buku</a>
                <a href="?page=form" class="bg-white text-indigo-700 px-3 py-1 rounded-lg font-semibold">Tambah</a>
                <a href="?page=logout" class="hover:underline" onclick="return confirm('Logout sekarang?')">Logout</a>
            <?php else: ?>
                <a href="?page=login" class="hover:underline">Login</a>
                <a href="?page=register" class="bg-white text-indigo-700 px-3 py-1 rounded-lg font-semibold">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="max-w-6xl mx-auto p-4">
    <?php if ($success): ?>
        <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($errors): ?>
        <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
            <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($page === 'login'): ?>
        <section class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow mt-10">
            <h1 class="text-2xl font-bold mb-2">Login</h1>
            <p class="text-sm text-slate-500 mb-5">Admin default: <b>admin</b> / <b>admin123</b></p>
            <form method="POST" class="space-y-4">
                <input class="w-full border rounded-lg px-3 py-2" type="text" name="username" placeholder="Username" required minlength="4">
                <input class="w-full border rounded-lg px-3 py-2" type="password" name="password" placeholder="Password" required minlength="6">
                <button class="w-full bg-indigo-700 hover:bg-indigo-800 text-white py-2 rounded-lg font-semibold" type="submit" name="login">Login</button>
            </form>
            <p class="mt-4 text-sm">Belum punya akun? <a class="text-indigo-700 font-semibold" href="?page=register">Register</a></p>
        </section>

    <?php elseif ($page === 'register'): ?>
        <section class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow mt-10">
            <h1 class="text-2xl font-bold mb-5">Register User</h1>
            <form method="POST" class="space-y-4">
                <input class="w-full border rounded-lg px-3 py-2" type="text" name="username" placeholder="Username minimal 4 karakter" required minlength="4">
                <input class="w-full border rounded-lg px-3 py-2" type="password" name="password" placeholder="Password minimal 6 karakter" required minlength="6">
                <button class="w-full bg-indigo-700 hover:bg-indigo-800 text-white py-2 rounded-lg font-semibold" type="submit" name="register">Daftar</button>
            </form>
            <p class="mt-4 text-sm">Sudah punya akun? <a class="text-indigo-700 font-semibold" href="?page=login">Login</a></p>
        </section>

    <?php elseif ($page === 'form'): ?>
        <section class="bg-white p-6 rounded-2xl shadow">
            <h1 class="text-2xl font-bold mb-5"><?= $editData ? 'Edit Buku' : 'Tambah Buku' ?></h1>
            <form method="POST" class="grid md:grid-cols-2 gap-4" onsubmit="return cekFormBuku()">
                <?php if ($editData): ?><input type="hidden" name="id" value="<?= e($editData['id']) ?>"><?php endif; ?>
                <div>
                    <label class="font-semibold">Judul</label>
                    <input id="judul" class="w-full border rounded-lg px-3 py-2 mt-1" type="text" name="judul" required value="<?= e($editData['judul'] ?? '') ?>">
                </div>
                <div>
                    <label class="font-semibold">Penulis</label>
                    <input class="w-full border rounded-lg px-3 py-2 mt-1" type="text" name="penulis" required value="<?= e($editData['penulis'] ?? '') ?>">
                </div>
                <div>
                    <label class="font-semibold">Kategori</label>
                    <select class="w-full border rounded-lg px-3 py-2 mt-1" name="kategori" required>
                        <?php foreach (['Teknologi','Database','Pemrograman','Novel','Umum'] as $kat): ?>
                            <option value="<?= e($kat) ?>" <?= (($editData['kategori'] ?? '') === $kat) ? 'selected' : '' ?>><?= e($kat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="font-semibold">Tahun Terbit</label>
                    <input class="w-full border rounded-lg px-3 py-2 mt-1" type="number" name="tahun_terbit" required min="1900" max="<?= date('Y') ?>" value="<?= e($editData['tahun_terbit'] ?? date('Y')) ?>">
                </div>
                <div>
                    <label class="font-semibold">Stok</label>
                    <input class="w-full border rounded-lg px-3 py-2 mt-1" type="number" name="stok" required min="0" value="<?= e($editData['stok'] ?? 0) ?>">
                </div>
                <div>
                    <label class="font-semibold">Harga</label>
                    <input class="w-full border rounded-lg px-3 py-2 mt-1" type="number" name="harga" required min="0" step="0.01" value="<?= e($editData['harga'] ?? 0) ?>">
                </div>
                <div class="md:col-span-2">
                    <label class="font-semibold">Deskripsi</label>
                    <textarea class="w-full border rounded-lg px-3 py-2 mt-1" name="deskripsi" rows="3"><?= e($editData['deskripsi'] ?? '') ?></textarea>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button class="bg-indigo-700 hover:bg-indigo-800 text-white px-4 py-2 rounded-lg font-semibold" type="submit" name="<?= $editData ? 'update_buku' : 'create_buku' ?>"><?= $editData ? 'Simpan Perubahan' : 'Simpan Buku' ?></button>
                    <a class="bg-slate-200 px-4 py-2 rounded-lg" href="?page=dashboard">Batal</a>
                </div>
            </form>
        </section>

    <?php else: ?>
        <?php
        $keyword = trim($_GET['q'] ?? '');
        if ($keyword !== '') {
            $like = '%' . $keyword . '%';
            $stmt = $conn->prepare('SELECT * FROM buku WHERE judul LIKE ? OR penulis LIKE ? OR kategori LIKE ? ORDER BY id DESC');
            $stmt->bind_param('sss', $like, $like, $like);
            $stmt->execute();
            $books = $stmt->get_result();
        } else {
            $books = $conn->query('SELECT * FROM buku ORDER BY id DESC');
        }
        ?>
        <section class="bg-white p-6 rounded-2xl shadow">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                <div>
                    <h1 class="text-2xl font-bold">Data Buku</h1>
                    <p class="text-sm text-slate-500">User bisa tambah/edit, admin bisa tambah/edit/hapus.</p>
                </div>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="page" value="dashboard">
                    <input class="border rounded-lg px-3 py-2" type="text" name="q" placeholder="Cari buku..." value="<?= e($keyword) ?>">
                    <button class="bg-slate-800 text-white px-4 py-2 rounded-lg" type="submit">Cari</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead class="bg-indigo-700 text-white">
                        <tr>
                            <th class="p-3 text-left">Judul</th>
                            <th class="p-3 text-left">Penulis</th>
                            <th class="p-3 text-left">Kategori</th>
                            <th class="p-3 text-left">Tahun</th>
                            <th class="p-3 text-left">Stok</th>
                            <th class="p-3 text-left">Harga</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($books && $books->num_rows > 0): ?>
                            <?php while ($row = $books->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="p-3 font-semibold"><?= e($row['judul']) ?><br><span class="font-normal text-xs text-slate-500"><?= e($row['deskripsi']) ?></span></td>
                                    <td class="p-3"><?= e($row['penulis']) ?></td>
                                    <td class="p-3"><?= e($row['kategori']) ?></td>
                                    <td class="p-3"><?= e($row['tahun_terbit']) ?></td>
                                    <td class="p-3"><?= e($row['stok']) ?></td>
                                    <td class="p-3">Rp <?= number_format((float)$row['harga'], 0, ',', '.') ?></td>
                                    <td class="p-3 whitespace-nowrap">
                                        <a class="text-indigo-700 font-semibold" href="?page=form&id=<?= e($row['id']) ?>">Edit</a>
                                        <?php if (is_admin()): ?>
                                            <a class="text-red-600 font-semibold ml-3" href="?page=delete&id=<?= e($row['id']) ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td class="p-4 text-center text-slate-500" colspan="7">Data tidak ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>
</main>

<script>
function cekFormBuku() {
    const judul = document.getElementById('judul');
    if (judul && judul.value.trim().length < 3) {
        alert('Judul buku minimal 3 karakter.');
        judul.focus();
        return false;
    }
    return true;
}
</script>
</body>
</html>
<?php $conn->close(); ?>
