<?php
// Cennik usług: dodaj kolejny wiersz do tablicy, a pojawi się w tabeli na stronie
$cennik = [
    ['nazwa' => 'Przegląd',               'cena' => 120],
    ['nazwa' => 'Przegląd generalny',     'cena' => 250],
    ['nazwa' => 'Wymiana opony lub dętki', 'cena' => 40],
    ['nazwa' => 'Centrowanie koła',       'cena' => 60],
    ['nazwa' => 'Wymiana łańcucha',       'cena' => 50],
];

// Bonus: powitanie z adresu, np. index.php?imie=Ola
$imie = (isset($_GET['imie']) && is_string($_GET['imie']) && trim($_GET['imie']) !== '')
    ? trim($_GET['imie'])
    : 'Gościu';

// Bonus: wymiary wykresu SVG liczone z tej samej tablicy co cennik
$wiersz = 52;                                       // wysokość jednego wiersza wykresu
$maks   = max(array_column($cennik, 'cena'));       // najwyższa cena = najdłuższy słupek
$wys    = count($cennik) * $wiersz;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Szprycha – serwis rowerowy. Przeglądy, naprawy i cennik usług.">
  <title>Szprycha – serwis rowerowy</title>
  <link rel="stylesheet" href="style.css">
  <script src="app.js" defer></script>
</head>
<body>

  <div class="top">
    <header class="site-header">
      <a class="logo" href="#">
        <svg viewBox="0 0 120 64" width="72" height="38" role="img" aria-labelledby="logo-tytul"
             fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
          <title id="logo-tytul">Szprycha</title>
          <circle cx="28" cy="42" r="18"/>
          <circle cx="92" cy="42" r="18"/>
          <polyline points="28,42 46,20 82,20 54,42 28,42" stroke="#e0c9ae"/>
          <polyline points="82,20 92,42" stroke="#e0c9ae"/>
        </svg>
        <span>Szprycha</span>
      </a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="menu" aria-label="Menu">
        <span class="menu-toggle__bar" aria-hidden="true"></span>
        <span class="menu-toggle__bar" aria-hidden="true"></span>
        <span class="menu-toggle__bar" aria-hidden="true"></span>
      </button>
      <nav class="menu" id="menu" aria-label="Menu główne">
        <a href="#uslugi">Usługi</a>
        <a href="#cennik">Cennik</a>
        <a href="#kontakt">Kontakt</a>
      </nav>
      <a class="btn-contact" href="#kontakt">Zadzwoń</a>
    </header>

    <div class="hero__content">
      <div class="hero__thumbs" aria-hidden="true">
      <svg class="thumb" viewBox="0 0 135 180" focusable="false">
        <defs><linearGradient id="t1" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#d4b48a"/><stop offset="1" stop-color="#6e533a"/></linearGradient></defs>
        <rect width="135" height="180" fill="url(#t1)"/>
        <g fill="none" stroke="#f6ead7" stroke-width="3" stroke-linecap="round">
          <circle cx="67" cy="90" r="46" stroke-width="6"/><line x1="67" y1="90" x2="113.0" y2="90.0"/><line x1="67" y1="90" x2="106.8" y2="113.0"/><line x1="67" y1="90" x2="90.0" y2="129.8"/><line x1="67" y1="90" x2="67.0" y2="136.0"/><line x1="67" y1="90" x2="44.0" y2="129.8"/><line x1="67" y1="90" x2="27.2" y2="113.0"/><line x1="67" y1="90" x2="21.0" y2="90.0"/><line x1="67" y1="90" x2="27.2" y2="67.0"/><line x1="67" y1="90" x2="44.0" y2="50.2"/><line x1="67" y1="90" x2="67.0" y2="44.0"/><line x1="67" y1="90" x2="90.0" y2="50.2"/><line x1="67" y1="90" x2="106.8" y2="67.0"/>
        </g>
        <circle cx="67" cy="90" r="7" fill="#f6ead7"/>
      </svg>
      <svg class="thumb" viewBox="0 0 135 180" focusable="false">
        <defs><linearGradient id="t2" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#9a8671"/><stop offset="1" stop-color="#2b231c"/></linearGradient></defs>
        <rect width="135" height="180" fill="url(#t2)"/>
        <g fill="none" stroke="#efe3d0">
          <circle cx="67" cy="90" r="42" stroke-width="12" stroke-dasharray="8.2 8.2"/>
          <circle cx="67" cy="90" r="30" stroke-width="4"/>
          <circle cx="67" cy="90" r="8" stroke-width="4"/>
        </g>
      </svg>
      <svg class="thumb" viewBox="0 0 135 180" focusable="false">
        <defs><linearGradient id="t3" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e7d7bd"/><stop offset="1" stop-color="#a28463"/></linearGradient></defs>
        <rect width="135" height="180" fill="url(#t3)"/>
        <g transform="rotate(35 67 90)" fill="#2a221b">
          <rect x="61" y="52" width="12" height="82" rx="6"/>
          <circle cx="67" cy="44" r="22" fill="none" stroke="#2a221b" stroke-width="10"/>
          <circle cx="67" cy="140" r="12"/>
        </g>
      </svg>
      </div>
      <h1 class="hero__title"><span>Szprycha</span> <span>Serwis rowerowy</span></h1>
      <p class="hero__lead">Twój rower w dobrych rękach. Przeglądy, naprawy i regulacje, żebyś wracał na trasę bez zmartwień.</p>
      <a class="btn" href="#kontakt">Umów serwis</a>
    </div>

    <div class="hero__visual">
      <svg class="hero__art" viewBox="0 0 400 560" preserveAspectRatio="xMidYMid slice" role="img" aria-labelledby="art-tytul">
        <title id="art-tytul">Ilustracja roweru na tle wzgórz w świetle zachodzącego słońca</title>
        <defs>
          <linearGradient id="niebo" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#d7bf9f"/><stop offset=".55" stop-color="#ecdcc3"/><stop offset="1" stop-color="#c2a582"/>
          </linearGradient>
        </defs>
        <rect width="400" height="560" fill="url(#niebo)"/>
        <circle cx="270" cy="180" r="92" fill="#f6ead6" opacity=".85"/>
        <path d="M0 400 Q110 330 220 375 T400 350 V560 H0Z" fill="#a58966"/>
        <path d="M0 470 Q130 430 250 462 T400 450 V560 H0Z" fill="#6c563f"/>
        <ellipse cx="213" cy="500" rx="150" ry="10" fill="#2a2018" opacity=".35"/>
        <g transform="translate(45 330) scale(2.8)" fill="none" stroke="#1f1812" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="28" cy="42" r="18"/>
          <circle cx="92" cy="42" r="18"/>
          <polyline points="28,42 46,20 82,20 54,42 28,42"/>
          <polyline points="82,20 92,42"/>
          <path d="M38 18 H54"/>
          <path d="M82 20 L79 11 H90"/>
          <circle cx="54" cy="42" r="4"/>
        </g>
      </svg>
      <p class="hero__caption">Naprawy, przeglądy i regulacje</p>
    </div>
  </div>

  <main>
    <p class="greeting">Cześć, <?= htmlspecialchars($imie) ?>!</p>

    <section id="uslugi">
      <h2>Usługi</h2>
      <div class="cards">
        <article class="card">
          <h3>Przegląd</h3>
          <p>Hamulce i przerzutki.</p>
          <p class="card__price">od 120 zł</p>
        </article>

        <article class="card card--highlight">
          <p class="card__badge">Najczęściej wybierane</p>
          <h3>Przegląd generalny</h3>
          <p>Pełna regulacja, smarowanie, centrowanie kół i sprawdzenie ramy.</p>
          <p class="card__price">od 250 zł</p>
        </article>

        <article class="card">
          <h3>Wymiana opon</h3>
          <p>Demontaż, wymiana opony lub dętki i napompowanie kół.</p>
          <p class="card__price">od 40 zł</p>
        </article>
      </div>
    </section>

    <section id="cennik">
      <h2>Cennik</h2>
      <table class="price-table">
        <caption class="visually-hidden">Cennik usług serwisowych</caption>
        <thead>
          <tr><th scope="col">Usługa</th><th scope="col" class="num">Cena</th></tr>
        </thead>
        <tbody>
          <?php foreach ($cennik as $usluga): ?>
          <tr>
            <td><?= htmlspecialchars($usluga['nazwa']) ?></td>
            <td class="num"><?= $usluga['cena'] ?> zł</td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h3 class="chart-title">Porównanie cen</h3>
      <svg class="chart" viewBox="0 0 400 <?= $wys ?>" role="img" aria-labelledby="wykres-tytul">
        <title id="wykres-tytul">Wykres słupkowy cen usług</title>
        <?php foreach ($cennik as $i => $usluga):
          $y    = $i * $wiersz;
          $szer = max(4, round(300 * $usluga['cena'] / $maks));
        ?>
        <text class="chart__label" x="0" y="<?= $y + 18 ?>"><?= htmlspecialchars($usluga['nazwa']) ?></text>
        <rect class="chart__bar" x="0" y="<?= $y + 26 ?>" width="<?= $szer ?>" height="16" rx="3"/>
        <text class="chart__value" x="<?= $szer + 8 ?>" y="<?= $y + 39 ?>"><?= $usluga['cena'] ?> zł</text>
        <?php endforeach; ?>
      </svg>
    </section>
  </main>

  <footer id="kontakt">
    <h2>Kontakt</h2>
    <address>
      ul. Gdańska 12, 85-006 Bydgoszcz<br>
      Telefon: <a href="tel:+48500100200">500 100 200</a><br>
      E-mail: <a href="mailto:serwis@szprycha.pl">serwis@szprycha.pl</a>
    </address>
    <p class="copy">&copy; 2026 Szprycha</p>
  </footer>

</body>
</html>