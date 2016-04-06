<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 13.03.2015
 * Time: 12:24
 */
        // tree view here

        $level = 0;
        foreach($trees as $n=>$category)
        {
            $ids = array();
            $root = false;
            if ($category->isRoot()) {
                $root = true;
                $ids = $category->getDescendantsIds();
            }
            if($category->level==$level)
                echo CHtml::closeTag('li')."\n";
            else if($category->level>$level)
                echo CHtml::openTag('ul', array(
                        'class'=>$level==0? "accordion_tree" : ""
                    ))."\n";
            else
            {
                echo CHtml::closeTag('li')."\n";

                for($i=$level-$category->level;$i;$i--)
                {
                    echo CHtml::closeTag('ul')."\n";
                    echo CHtml::closeTag('li')."\n";
                }
            }

            echo CHtml::openTag('li', array(
                "data-id"=>$category->id,
                "class"=>$root ? "root" : "",
                "data-childrens"=> $root ? json_encode($ids) : ""
            ));
            echo CHtml::openTag('div', array(
                'class'=>'list-group-item accordion',
                'style'=>'width:250px'
            ));
            echo CHtml::openTag('span', array(
                "class"=>$category->hasDescendants() ? "glyphicon glyphicon-minus" : "glyphicon",
                "style"=>"color:lightblue; margin-right:5px;"
            ));
            echo CHtml::closeTag('span');
            $this->widget(
                'booster.widgets.TbEditableField',
                array(
                    'type' => 'text',
                    'model' => $category,
                    'attribute' => 'name', // $model->name will be editable
                    'url' => Yii::app()->createUrl("/configuration/tree/update", array("model"=>"Tree")), //url for submit data
                    'htmlOptions' => array(
                        'style' => 'margin-right:10px',
                    ),
                )
            );
            echo CHtml::link("", '#', array(
                "class"=>"glyphicon glyphicon-remove deleteBranch",
                'style' => 'margin-left:10px',
                'value'=>$category->id
            ));

            echo CHtml::link("", "#", array(
                "class"=>"glyphicon glyphicon-plus addBranchId",
                'style' => 'margin-left:10px',
                'data-toggle' => 'modal',
                'data-target' => '#newBranch',
                'value' => $category->id
            ));

            if($category->level > 2)
            {
                echo CHtml::link("", "#", array(
                    'class'=>'glyphicon glyphicon-pencil editBranchId',
                    'style' => 'margin-left:10px',
                    'data-toggle' => 'modal',
                    'data-target' => '#newBranch',
                    'value' => $category->id
                ));
            }


            echo CHtml::closeTag('div');
            $level=$category->level;
        }

        for($i=$level;$i;$i--)
        {
            echo CHtml::closeTag('li')."\n";
            echo CHtml::closeTag('ul')."\n";
        }
?>