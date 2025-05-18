<?php
$title = "Home Page";
ob_start();
?>
<div class="palette"> 
    <div class="color-box coffee-dark">#4B2E2B<br>Основний (кава)</div> 
    <div class="color-box cream">#F3E9DC<br>Фон</div> 
    <div class="color-box tea-green">#8A9A5B<br>Акцент (чай)</div> 
    <div class="color-box caramel">#C9A66B<br>Акцент (карамель)</div> 
    <div class="color-box gray-text">#555555<br>Основний текст</div> 
    <div class="color-box berry">#7B3F3F<br>Акцент (ягоди)</div> 
    <div class="color-box deep-green">#46644C<br>Акцент (зел. чай)</div> 
    <div class="color-box text-dark">#2B2B2B<br>Заголовки</div> 
</div> 
<div class="text-section bg-cream"> 
    <div class="h1">Заголовок H1 — Playfair Display</div> 
    <div class="h2">Підзаголовок H2 — Playfair Display</div> 
    <p class="body-text">Це приклад основного тексту, що використовує шрифт Noto Serif. Такий стиль пасує до контенту про каву та чай: затишний, читабельний, з класичною естетикою.</p> 
    <p class="caption">Це підпис або вторинний текст — Montserrat</p> 
</div> 
<div class="text-section bg-coffee-dark"> 
    <div class="h1">Контрастний заголовок</div> 
    <p class="body-text">Цей блок демонструє текст поверх темного фону.</p> 
</div> 
<div class="text-section bg-caramel"> 
    <div class="h2">Блок на карамельному фоні</div> 
    <p class="body-text">Можна використовувати для акцентних секцій або кнопок.</p> 
</div> 
<?php
$content = ob_get_clean();
include 'layout.php';
?>