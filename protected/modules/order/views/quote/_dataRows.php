<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.03.2016
 * Time: 12:41
 */
    foreach ($data as $dataRow) {
        ?>
        <tr>
            <td><?=$dataRow["sector"]?></td>
            <td><?=$dataRow["row"]?></td>
            <td><?=$dataRow["placeFrom"]?></td>
            <td><?=$dataRow["placeTo"]?></td>
            <td><span class="row-sum-<?=$model->id?>"><?=$dataRow["sum"]?></span> грн.</td>
            <td><span class="row-count-<?=$model->id?>"><?=$dataRow["count"]?></span> шт</td>
        </tr>
        <?php
    }
?>