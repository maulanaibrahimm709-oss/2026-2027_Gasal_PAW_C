<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $level = (int)($_POST['level'] ?? 0);
    $err = '';
    if ($nama === '') $err = 'Nama wajib diisi.';
    elseif (!preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $username)) $err = 'Username 3-30 karakter (huruf, angka, titik, strip, underscore).';
    elseif (!in_array($level, [1, 2], true)) $err = 'Level tidak valid.';
    elseif ($password !== '' && strlen($password) < 6) $err = 'Password minimal 6 karakter.';
    if ($err !== '') {
        setFlash('error', $err);
        header('Location: ?url=/user/edit&id=' . $id);
        exit;
    }
    try {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE `user` SET nama = ?, username = ?, password = ?, level = ? WHERE id = ?");
            $stmt->bind_param("sssii", $nama, $username, $hash, $level, $id);
        } else {
            $stmt = $conn->prepare("UPDATE `user` SET nama = ?, username = ?, level = ? WHERE id = ?");
            $stmt->bind_param("ssii", $nama, $username, $level, $id);
        }
        $stmt->execute();
        if ($id === userId()) {                    // sinkronkan session bila mengubah akun sendiri
            $_SESSION['user']['nama'] = $nama;
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['level'] = $level;
        }
        setFlash('ok', 'User berhasil diubah.');
        header('Location: ?url=/user');
        exit;
    } catch (mysqli_sql_exception $ex) {
        setFlash('error', $ex->getCode() == 1062 ? 'Username sudah dipakai.' : 'Gagal menyimpan data.');
        header('Location: ?url=/user/edit&id=' . $id);
        exit;
    }
}
