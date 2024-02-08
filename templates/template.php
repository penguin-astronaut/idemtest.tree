<?php

/**
 * @var array $arResult
 */

?>

<?php foreach ($arResult['ITEMS'] as $section):?>
<p><b><?= $section['name'] ?></b></p>
<ul>
    <?php foreach ($section['elements'] as $element):?>
        <li><?= $element['name'] . ' ' . $element['tags'] ?></li>
    <?php endforeach;?>
</ul>
<hr>
<?php endforeach;?>