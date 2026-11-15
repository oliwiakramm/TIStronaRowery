<?php
require __DIR__ . '/auth.php';   // bez logowania odsyła do login.php
require __DIR__ . '/baza.php';

$blad = '';
$nazwa = '';
$cena = '';

// --- obsługa formularza: akcja=dodaj ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['akcja'] ?? '') === 'dodaj') {
    $nazwa = trim($_POST['nazwa'] ?? '');
    $cena  = filter_var($_POST['cena'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    // 1. sprawdź
    if ($nazwa === '' || mb_strlen($nazwa) > 100) {
        $blad = 'Podaj nazwę usługi (maksymalnie 100 znaków).';
    } elseif ($cena === false) {
        $blad = 'Cena musi być liczbą całkowitą, minimum 1.';
    } else {
        // 2. zapisz przez prepare()
        $z = $pdo->prepare('INSERT INTO uslugi (nazwa, cena) VALUES (:nazwa, :cena)');
        $z->execute(['nazwa' => $nazwa, 'cena' => $cena]);

        // 3. przekieruj (F5 nie wyśle formularza ponownie)
        $_SESSION['komunikat'] = 'Dodano usługę.';
        header('Location: admin.php');
        exit;
    }
}

// komunikat po udanym dodaniu (pokazuje się raz)
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
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="panel panel--wide">
    <h1>Witaj, <?= htmlspecialchars($_SESSION['uzytkownik']) ?></h1>
    <p>
      <a href="index.php">Strona główna</a> |
      <a href="wyloguj.php">Wyloguj</a>
    </p>

    <?php if ($komunikat): ?>
      <p class="flash" role="status"><?= htmlspecialchars($komunikat) ?></p>
    <?php endif; ?>

    <h2>Usługi</h2>
    <table class="price-table">
      <caption class="visually-hidden">Wszystkie usługi z bazy</caption>
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Usługa</th>
          <th scope="col" class="num">Cena</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($uslugi as $u): ?>
        <tr>
          <td><?= (int) $u['id'] ?></td>
          <td><?= htmlspecialchars($u['nazwa']) ?></td>
          <td class="num"><?= (int) $u['cena'] ?> zł</td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$uslugi): ?>
        <tr><td colspan="3">Brak usług.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <h2>Dodaj usługę</h2>

    <?php if ($blad): ?>
      <p class="form-error" role="alert"><?= htmlspecialchars($blad) ?></p>
    <?php endif; ?>

    <form method="post" class="form">
      <input type="hidden" name="akcja" value="dodaj">
      <label for="nazwa">Nazwa</label>
      <input id="nazwa" name="nazwa" required maxlength="100" value="<?= htmlspecialchars($nazwa) ?>">
      <label for="cena">Cena (zł)</label>
      <input id="cena" name="cena" type="number" min="1" required value="<?= htmlspecialchars((string) ($cena === false ? '' : $cena)) ?>">
      <button class="btn">Dodaj</button>
    </form>
  </main>
</body>
</html>