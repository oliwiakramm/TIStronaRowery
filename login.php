
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <main class="container min-vh-100 d-flex align-items-center py-4">
    <div class="row justify-content-center w-100">
      <div class="col-12 col-sm-9 col-md-6 col-lg-4">

        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h3 mb-4">Logowanie</h1>

            <?php if ($blad): ?>
              <div class="alert alert-danger" role="alert"><?= htmlspecialchars($blad) ?></div>
            <?php endif; ?>

            <form method="post" action="login.php">
              <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input type="text" id="login" name="login" class="form-control"
                       autocomplete="username" required
                       value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
              </div>
              <div class="mb-4">
                <label for="haslo" class="form-label">Hasło</label>
                <input type="password" id="haslo" name="haslo" class="form-control"
                       autocomplete="current-password" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Zaloguj się</button>
            </form>
          </div>
        </div>

        <p class="text-center mt-3"><a href="index.php">&larr; Wróć na stronę główną</a></p>
      </div>
    </div>
  </main>
</body>
</html>
