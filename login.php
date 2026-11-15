<?php
session_start();
require __DIR__ . '/baza.php';

// zalogowany użytkownik nie musi widzieć formularza
if (!empty($_SESSION['uzytkownik'])) {
    header('Location: admin.php');
    exit;
}

$blad = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $z = $pdo->prepare(
        'SELECT login, haslo_hash FROM uzytkownicy WHERE login = :login');
    $z->execute(['login' => trim($_POST['login'] ?? '')]);
    $u = $z->fetch();

    if ($u && password_verify($_POST['haslo'] ?? '', $u['haslo_hash'])) {
        session_regenerate_id(true);
        $_SESSION['uzytkownik'] = $u['login'];
        header('Location: admin.php');
        exit;
    }
    $blad = 'Błędny login lub hasło.';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Logowanie – Szprycha</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="panel">
    <h1>Logowanie</h1>

    <?php if ($blad): ?>
      <p class="form-error" role="alert"><?= htmlspecialchars($blad) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php" class="form">
      <label for="login">Login</label>
      <input type="text" id="login" name="login" autocomplete="username" required
             value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">

      <label for="haslo">Hasło</label>
      <input type="password" id="haslo" name="haslo" autocomplete="current-password" required>

      <button type="submit" class="btn">Zaloguj się</button>
    </form>

    <p><a href="index.php">&larr; Wróć na stronę główną</a></p>
  </main>
</body>
</html>