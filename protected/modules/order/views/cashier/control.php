<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 10.09.15
 * Time: 7:44
 * @var $role_id int
 * @var $cashiers
 */
?>

<div class="col-lg-12">

    <h1>
        Контроль каси
    </h1>
    <div class="col-lg-6">
        <h3>
            Каса
        </h3>
        <?php echo CHtml::dropDownList("kasa", $role_id, Role::getRoleList(), array(
            "class"=>"form-control",
            "empty"=>"------"
        ))
        ?>
    </div>
    <div class="col-lg-6">
        <h3>
            Касир
        </h3>
        <?php echo CHtml::dropDownList("cashier", Yii::app()->user->id, $cashiers, array(
            "class"=>"form-control",
            "empty"=>"------"
        ))
        ?>
        <p>
            <span class="text-mutted">
                    Старший касир для цього касира
            </span>
        </p>
        <?php foreach ($admins as $user) {
            echo $user->fullName." ".$user->email."<br/>";
        }
        ?>

    </div>


    <div class="col-lg-6">
        <?php
        $this->widget("booster.widgets.TbDateRangePicker", array(
           'name'=>"dateRange"
        ));
        ?>
    </div>
    <div class="col-lg-6"></div>
    <div class="col-lg-12">

    </div>

</div>

