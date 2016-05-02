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
?>
<html>
<head>
    <meta name="description" content="Вакансии в Нягани, работа в Нягани, центр занятости, биржа труда" />
    <meta name="viewport" content="width=device-width">
    <title>Работа в Нягани - ny86.ru</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <script src="/js/common.js"></script>
    <script>
        var vaclist = {
            <?php
                foreach ($res as $vac) {
                ?>"<?=trim(preg_replace('/\s+|"|\'/', ' ', $vac['search']))?>": "<?=$vac['hash']?>",
            <?
            }
            ?>
        };
    </script>
</head>
<body>
<header class="container">
    <h1 class="maintitle"><a href="http://vk.com/ny86pub">Работа в Нягани</a></h1>
    <div id="search">
        <input type="text" id="searchfield" placeholder="Поиск..."/>
        <div id="searchstring"></div>
        <div class="info"><?=$listdate?></div>
    </div>
</header>
<section class="container">
    <? foreach ($res as $vacancy): ?>
        <div class="vacancy" id="<?=$vacancy['hash']?>">
            <div class="line">
                <span class="title"><?=$vacancy['profession']?></span>
                <span class="salary"><?=number_format($vacancy['salary'], 0, '.', ',')?></span>
            </div>
            <span><?=$vacancy['organisation']?></span>
            <span><?=$vacancy['additions']?></span>
            <div class="confidence">
                <span><?= nl2br($vacancy['address'])?></span>
                <span><?= nl2br($vacancy['contacts'])?></span>
            </div>
        </div>
    <? endforeach; ?>
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