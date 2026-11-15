<?php
// Połączenie z bazą SQLite. Plik bazy powstanie obok tego pliku (baza.sqlite).
$pdo = new PDO('sqlite:' . __DIR__ . '/baza.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);          // błędy jako wyjątki
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);     // wiersze jako tablice asocjacyjne
