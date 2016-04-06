<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 10.09.15
 * Time: 6:20
 * @var $user User
 * @var $sum
 * @var $cash
 */

?>

<h3>Увага!</h3>
<p>
    Касир <?php echo $user->getFullName()." (".$user->email.")"?> <?= Yii::app()->dateFormatter->format("dd.MM.yyyy", time())?> вніс розмір денної каси <?php echo $cash-$sum > 0 ? "менший" : "більший";?> ніж було підраховано системою на <?php echo number_format($cash-$sum, 2, ".", " ")?> грн.
</p>
<p>
    Сума за підрахунками системи: <?php echo number_format($sum, 2, ".", " ")?> грн.
</p>
<p>
    Заявлена сума: <?php echo number_format($cash, 2, ".", " ")?> грн.
</p>

