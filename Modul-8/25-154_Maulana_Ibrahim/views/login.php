<?php
try { siapkanTabelAuth($conn); } catch (Exception $e) { setFlash('error', 'Database belum siap: ' . $e->getMessage()); }
$old = $_SESSION['login_old'] ?? '';
unset($_SESSION['login_old']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-wrap">
        <h2>Login Sistem Penjualan</h2>
        <?php tampilFlash(); ?>
        <form method="post" action="index.php?url=/api/login" autocomplete="off">
            <label>Username :
                <input type="text" name="username" value="<?= htmlspecialchars($old); ?>" placeholder="masukkan username..." autofocus required>
            </label>
            <label>Password :
                <input type="password" name="password" placeholder="masukkan password..." required>
            </label>
            <button type="submit" style="width:100%;">Login</button>
        </form>
    </div>
</body>
</html>
