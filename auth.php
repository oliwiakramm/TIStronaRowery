<?php
// Strażnik: dołącz na początku każdej strony, która ma być tylko dla zalogowanych.
session_start();

if (empty($_SESSION['uzytkownik'])) {
    header('Location: login.php');
    exit;
}