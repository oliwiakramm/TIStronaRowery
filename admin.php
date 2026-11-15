<?php
require __DIR__ . '/auth.php';   // bez logowania odsyła do login.php
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
  <main class="panel">
    <h1>Witaj, <?= htmlspecialchars($_SESSION['uzytkownik']) ?></h1>
    <p>To jest panel administratora.</p>
    <p>
      <a href="index.php">Strona główna</a> |
      <a href="wyloguj.php">Wyloguj</a>
    </p>
  </main>
</body>
</html>