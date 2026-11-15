
<?php
require __DIR__ . '/auth.php';   // bez logowania odsyła do login.php
require __DIR__ . '/baza.php';

$blad = '';
$nazwa = '';
$cena = '';
$akcja = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['akcja'] ?? '') : '';

// --- akcja=dodaj ---
if ($akcja === 'dodaj') {
    $nazwa = trim($_POST['nazwa'] ?? '');
    $cena  = filter_var($_POST['cena'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($nazwa === '' || mb_strlen($nazwa) > 100) {
        $blad = 'Podaj nazwę usługi (maksymalnie 100 znaków).';
    } elseif ($cena === false) {
        $blad = 'Cena musi być liczbą całkowitą, minimum 1.';
    } else {
        $z = $pdo->prepare('INSERT INTO uslugi (nazwa, cena) VALUES (:nazwa, :cena)');
        $z->execute(['nazwa' => $nazwa, 'cena' => $cena]);

        $_SESSION['komunikat'] = 'Dodano usługę.';
        header('Location: admin.php');
        exit;
    }
}

// --- akcja=usun ---
if ($akcja === 'usun') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0) {
        $z = $pdo->prepare('DELETE FROM uslugi WHERE id = :id');
        $z->execute(['id' => $id]);
        $_SESSION['komunikat'] = 'Usunięto usługę.';
    }
    header('Location: admin.php');
    exit;
}

// komunikat po dodaniu lub usunięciu (pokazuje się raz)
$komunikat = $_SESSION['komunikat'] ?? '';
unset($_SESSION['komunikat']);

$uslugi = $pdo->query('SELECT id, nazwa, cena FROM uslugi ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel – Szprycha</title>
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
    <h1 class="h3 mb-4">Witaj, <?= htmlspecialchars($_SESSION['uzytkownik']) ?></h1>

    <?php if ($komunikat): ?>
      <div class="alert alert-success" role="status"><?= htmlspecialchars($komunikat) ?></div>
    <?php endif; ?>
    <?php if ($blad): ?>
      <div class="alert alert-danger" role="alert"><?= htmlspecialchars($blad) ?></div>
    <?php endif; ?>

    <div class="row g-4">

      <!-- tabela usług (na telefonie na górze) -->
      <div class="col-12 col-lg-7">
        <h2 class="h5">Usługi</h2>
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle bg-white">
            <caption class="visually-hidden">Wszystkie usługi z bazy</caption>
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Usługa</th>
                <th scope="col" class="text-end">Cena</th>
                <th scope="col" class="text-end">Akcje</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($uslugi as $usluga): ?>
              <tr>
                <td><?= (int) $usluga['id'] ?></td>
                <td><?= htmlspecialchars($usluga['nazwa']) ?></td>
                <td class="text-end"><?= (int) $usluga['cena'] ?> zł</td>
                <td class="text-end">
                  <form method="post" class="d-inline"
                        onsubmit="return confirm('Na pewno usunąć tę usługę?');">
                    <input type="hidden" name="akcja" value="usun">
                    <input type="hidden" name="id" value="<?= (int) $usluga['id'] ?>">
                    <button class="btn btn-sm btn-outline-danger">Usuń</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (!$uslugi): ?>
              <tr><td colspan="4" class="text-center text-muted">Brak usług.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- formularz (na telefonie schodzi pod tabelę) -->
      <div class="col-12 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5 mb-3">Dodaj usługę</h2>
            <form method="post">
              <input type="hidden" name="akcja" value="dodaj">
              <div class="mb-3">
                <label for="nazwa" class="form-label">Nazwa</label>
                <input id="nazwa" name="nazwa" class="form-control" required maxlength="100"
                       value="<?= htmlspecialchars($nazwa) ?>">
              </div>
              <div class="mb-3">
                <label for="cena" class="form-label">Cena (zł)</label>
                <input id="cena" name="cena" type="number" min="1" class="form-control" required
                       value="<?= htmlspecialchars((string) ($cena === false ? '' : $cena)) ?>">
              </div>
              <button class="btn btn-primary">Dodaj</button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </main>
</body>
</html>
