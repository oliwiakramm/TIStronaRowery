
<?php
// Uruchom w terminalu:  php setup.php
// Własne hasło admina:  php setup.php MojeHaslo
// Można uruchamiać wielokrotnie: skrypt niczego nie dubluje i dokłada brakującą kolumnę.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Ten skrypt uruchamia się tylko z terminala.');
}

require __DIR__ . '/baza.php';

$haslo = $argv[1] ?? 'TwojeHaslo';   // ZMIEŃ na własne hasło (i nie wpisuj go do README)

$uslugi = [
    ['nazwa' => 'Przegląd',                'cena' => 120, 'popularnosc' => 85],
    ['nazwa' => 'Przegląd generalny',      'cena' => 250, 'popularnosc' => 60],
    ['nazwa' => 'Wymiana opony lub dętki', 'cena' => 40,  'popularnosc' => 70],
    ['nazwa' => 'Centrowanie koła',        'cena' => 60,  'popularnosc' => 40],
];

// --- tabele ---
$pdo->exec('CREATE TABLE IF NOT EXISTS uzytkownicy (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  login TEXT NOT NULL UNIQUE,
  haslo_hash TEXT NOT NULL
)');

$pdo->exec('CREATE TABLE IF NOT EXISTS uslugi (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nazwa TEXT NOT NULL,
  cena INTEGER NOT NULL,
  popularnosc INTEGER NOT NULL DEFAULT 0
)');

// --- starsza baza bez kolumny popularnosc: dołóż ją ---
$kolumny = array_column($pdo->query('PRAGMA table_info(uslugi)')->fetchAll(), 'name');
if (!in_array('popularnosc', $kolumny, true)) {
    $pdo->exec('ALTER TABLE uslugi ADD COLUMN popularnosc INTEGER NOT NULL DEFAULT 0');
    $z = $pdo->prepare('UPDATE uslugi SET popularnosc = :p WHERE nazwa = :n');
    foreach ($uslugi as $u) {
        $z->execute(['p' => $u['popularnosc'], 'n' => $u['nazwa']]);
    }
}

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
    $z = $pdo->prepare('INSERT INTO uslugi (nazwa, cena, popularnosc) VALUES (:nazwa, :cena, :popularnosc)');
    foreach ($uslugi as $u) {
        $z->execute($u);
    }
}

echo "Baza gotowa.\n";
