<?php
// Uruchom raz w terminalu:  php setup.php
// Własne hasło admina:      php setup.php MojeHaslo
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Ten skrypt uruchamia się tylko z terminala.');
}

require __DIR__ . '/baza.php';

$haslo = $argv[1] ?? 'TwojeHaslo';   // ZMIEŃ na własne hasło

// --- tabele ---
$pdo->exec('CREATE TABLE IF NOT EXISTS uzytkownicy (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  login TEXT NOT NULL UNIQUE,
  haslo_hash TEXT NOT NULL
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS uslugi (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nazwa TEXT NOT NULL,
  cena INTEGER NOT NULL
)');

// --- konto admina, gdy tabela jest pusta ---
if ((int) $pdo->query('SELECT COUNT(*) FROM uzytkownicy')->fetchColumn() === 0) {
    $z = $pdo->prepare('INSERT INTO uzytkownicy (login, haslo_hash) VALUES (:login, :hash)');
    $z->execute([
        'login' => 'admin',
        'hash'  => password_hash($haslo, PASSWORD_DEFAULT),
    ]);
}

// --- 4 usługi, gdy tabela jest pusta ---
if ((int) $pdo->query('SELECT COUNT(*) FROM uslugi')->fetchColumn() === 0) {
    $z = $pdo->prepare('INSERT INTO uslugi (nazwa, cena) VALUES (:nazwa, :cena)');
    $uslugi = [
        ['nazwa' => 'Przegląd',                'cena' => 120],
        ['nazwa' => 'Przegląd generalny',      'cena' => 250],
        ['nazwa' => 'Wymiana opony lub dętki', 'cena' => 40],
        ['nazwa' => 'Centrowanie koła',        'cena' => 60],
    ];
    foreach ($uslugi as $u) {
        $z->execute($u);
    }
}

echo "Baza gotowa.\n";