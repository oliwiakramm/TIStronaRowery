<?php
require __DIR__ . '/auth.php';   // strażnik
require __DIR__ . '/baza.php';

$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

$z = $pdo->prepare('SELECT id, nazwa, cena, popularnosc FROM uslugi WHERE id = :id');
$z->execute(['id' => $id]);
$usluga = $z->fetch();

if (!$usluga) {                  // nie ma takiej usługi
    header('Location: admin.php');
    exit;
}

$blad = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_sprawdz();

    $nazwa = trim($_POST['nazwa'] ?? '');
    $cena  = filter_var($_POST['cena'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $pop   = filter_var($_POST['popularnosc'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);

    // po błędzie formularz pokazuje to, co wpisała użytkowniczka
    $usluga['nazwa'] = $nazwa;
    $usluga['cena'] = $_POST['cena'] ?? '';
    $usluga['popularnosc'] = $_POST['popularnosc'] ?? '';

    if ($nazwa === '' || mb_strlen($nazwa) > 100) {
        $blad = 'Podaj nazwę usługi (maksymalnie 100 znaków).';
    } elseif ($cena === false) {
        $blad = 'Cena musi być liczbą całkowitą, minimum 1.';
    } elseif ($pop === false) {
        $blad = 'Popularność musi być liczbą od 0 do 100.';
    } else {
        $z = $pdo->prepare(
            'UPDATE uslugi SET nazwa = :nazwa, cena = :cena, popularnosc = :pop WHERE id = :id');
        $z->execute(['nazwa' => $nazwa, 'cena' => $cena, 'pop' => $pop, 'id' => $id]);

        $_SESSION['komunikat'] = 'Zapisano zmiany.';
        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edycja usługi – Szprycha</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex flex-wrap align-items-center gap-2">
      <a class="navbar-brand me-auto" href="index.php">Szprycha</a>
      <span class="navbar-text">Zalogowano: <?= htmlspecialchars($_SESSION['uzytkownik']) ?></span>
      <a class="btn btn-outline-light btn-sm" href="wyloguj.php">Wyloguj</a>
    </div>
  </nav>

  <main class="container py-4">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <p><a href="admin.php">&larr; Wróć do panelu</a></p>

        <div class="card shadow-sm">
          <div class="card-body">
            <h1 class="h4 mb-3">Edytuj usługę</h1>

            <?php if ($blad): ?>
              <div class="alert alert-danger" role="alert"><?= htmlspecialchars($blad) ?></div>
            <?php endif; ?>

            <form method="post">
              <?= csrf_pole() ?>
              <input type="hidden" name="id" value="<?= (int) $usluga['id'] ?>">

              <div class="mb-3">
                <label for="nazwa" class="form-label">Nazwa</label>
                <input id="nazwa" name="nazwa" class="form-control" required maxlength="100"
                       value="<?= htmlspecialchars((string) $usluga['nazwa']) ?>">
              </div>
              <div class="mb-3">
                <label for="cena" class="form-label">Cena (zł)</label>
                <input id="cena" name="cena" type="number" min="1" class="form-control" required
                       value="<?= htmlspecialchars((string) $usluga['cena']) ?>">
              </div>
              <div class="mb-3">
                <label for="popularnosc" class="form-label">Popularność (0–100)</label>
                <input id="popularnosc" name="popularnosc" type="number" min="0" max="100" class="form-control" required
                       value="<?= htmlspecialchars((string) $usluga['popularnosc']) ?>">
              </div>

              <button class="btn btn-primary">Zapisz</button>
              <a href="admin.php" class="btn btn-outline-secondary">Anuluj</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>