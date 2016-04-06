<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 16.09.15
 * Time: 13:25
 * @var $class
 * @var $macro
 * @var $bitMapUrl
 * @var $cartUrl
 */
?>

<div id="editor_cont" class="<?=$class?>" data-hasMacro="<?= $macro?>" <?= $macro&&strpos($class, "preview")!==false?"data-funzones='".json_encode($funzones)."'":""?>>
    <div id="svg_overflow">
        <div id="svg_cont" style="width: 0; height: 0;" <?= ($bitMapUrl?"data-bitMapUrl='".$bitMapUrl."'":"")?> <?= ($cartUrl?"data-cartUrl='".$cartUrl."'":"")?>>

        </div>
    </div>
</div>
