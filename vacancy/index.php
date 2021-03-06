<?php
require_once __DIR__ . '/../mrpropper/db/sqlite.php';
$db = new Model();

$res = $db->getOne($_GET['id']);
/*
 *    profession
      organisation
      additions
      salary
      address
      contacts
      search
 */
if (isset($res[0])) {
    $title = $res[0]['profession'];
} else {
    header("HTTP/1.0 404 Not Found");
}
?>
<html>
<head>
    <? if ($title): ?>
        <meta name="description" content="Вакансия <?= $title?> в Нягани, а также много других на сайте. Вакансии центра занятости и отдельных предпринимателей." />
    <? else: ?>
        <meta name="description" content="Вакансии в Нягани, работа в Нягани, центр занятости, биржа труда" />
    <? endif; ?>
    <meta name="viewport" content="width=device-width">
    <? if ($title): ?>
        <title><?=$title?> в Нягани</title>
    <? else: ?>
        <title>Ошибка 404</title>
    <? endif; ?>
    <meta charset="UTF-8" />
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="/css/common.css">
</head>
<body>
<header class="container">
    <h1 class="maintitle"><a href="/">Работа в Нягани</a></h1>
</header>
<section class="container">
    <? if ($title): ?>
        <div class="title margin-bottom">
            <a href="/">показать другие вакансии</a>
        </div>
        <? foreach ($res as $vacancy): ?>
            <?php
            $shortTime = DateTime::createFromFormat('Ymd', $vacancy['date']);
            $formatD = $shortTime->format('d.m.Y');
            ?>
            <div class="vacancy" id="<?=$vacancy['hash']?>">
                <div class="line">
                    <span class="title"><?=$vacancy['profession']?></span>
                    <span class="salary"><?=number_format($vacancy['salary'], 0, '.', ',')?></span>
                    <span class="addition"><?=$formatD?></span>
                </div>
                <span><?=$vacancy['organisation']?></span>
                <span><?=$vacancy['additions']?></span>
                <div class="confidence">
                    <span><?= nl2br($vacancy['address'])?></span>
                    <span><?= nl2br($vacancy['contacts'])?></span>
                </div>
            </div>
        <? endforeach; ?>
    <? else: ?>
        <h3>Ошибка 404</h3>
        <div class="title margin-bottom">
            Вакансия не найдена,
            <a href="/">показать другие</a>
        </div>
    <? endif; ?>
</section>
<section class="container">
    <a href="http://borisd.ru">&copy; borisd.ru</a>
</section>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter23990113 = new Ya.Metrika({
                    id:23990113,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/23990113" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>