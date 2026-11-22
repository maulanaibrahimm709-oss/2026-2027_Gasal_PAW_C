<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?url=/login'); exit; }

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$_SESSION['login_old'] = $username;

if ($username === '' || $password === '') {
    setFlash('error', 'Username dan password wajib diisi.');
    header('Location: index.php?url=/login'); exit;
}

try {
    siapkanTabelAuth($conn);
    $stmt = $conn->prepare("SELECT id, nama, username, password, level FROM `user` WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
} catch (Exception $e) {
    setFlash('error', 'Terjadi kesalahan database. Pastikan KampusDB sudah dibuat.');
    header('Location: index.php?url=/login'); exit;
}

if (!$u || !password_verify($password, $u['password'])) {
    setFlash('error', 'Username atau password salah.');
    header('Location: index.php?url=/login'); exit;
}

session_regenerate_id(true);                       // cegah session fixation
unset($_SESSION['login_old']);
$_SESSION['user'] = [                              // set session
    'id'       => (int)$u['id'],
    'nama'     => $u['nama'],
    'username' => $u['username'],
    'level'    => (int)$u['level'],
];
header('Location: index.php?url=/'); exit;
