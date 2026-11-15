<?php
// Strażnik: dołącz na początku każdej strony, która ma być tylko dla zalogowanych.
session_start();

if (empty($_SESSION['uzytkownik'])) {
    header('Location: login.php');
    exit;
}

// --- Token CSRF: chroni formularze panelu przed wysłaniem z obcej strony ---
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

// ukryte pole do wstawienia w każdym formularzu POST (przez echo csrf_pole())
function csrf_pole(): string
{
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

// wywołaj na początku obsługi POST
function csrf_sprawdz(): void
{
    $t = $_POST['csrf'] ?? '';
    if (!is_string($t) || !hash_equals(csrf_token(), $t)) {
        http_response_code(403);
        exit('Nieprawidłowy token formularza. Wróć do panelu i spróbuj ponownie.');
    }
}