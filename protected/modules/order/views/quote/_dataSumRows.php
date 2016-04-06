<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.03.2016
 * Time: 12:44
 */
$sumPrice = 0;
$sumCount = 0;
foreach ($data as $dataRow) {
    $sumPrice += $dataRow["sum"];
    $sumCount += $dataRow["count"];
}
?>
<tr class="font-bold">
    <td colspan="4" class="text-right">Всього:</td>
    <td><span class="all-sum-<?=$model->id?>"><?=$sumPrice?></span> грн.</td>
    <td><span class="all-count-<?=$model->id?>"><?=$sumCount?></span> шт</td>
</tr>
