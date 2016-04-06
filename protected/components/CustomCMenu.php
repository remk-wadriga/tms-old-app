<?php
/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 13.07.15
 * Time: 19:51
 */
Yii::import("zii.widgets.CMenu");
class CustomCMenu extends CMenu{

    protected function normalizeItems($items,$route,&$active)
    {
        foreach($items as $i=>$item)
        {

            if(isset($item['url'])&&is_array($item['url'])&&!Yii::app()->authManager->checkAccess(Yii::app()->user->role, Yii::app()->user->id, array(), $item['url'][0]))
            {
                unset($items[$i]);
                continue;
            }
			if(!isset($item['label']))
                $item['label']='';
			if($this->encodeLabel)
                $items[$i]['label']=CHtml::encode($item['label']);
			$hasActiveChild=false;
			if(isset($item['items']))
            {
                $items[$i]['items']=$this->normalizeItems($item['items'],$route,$hasActiveChild);
                if(empty($items[$i]['items']) && $this->hideEmptyItems)
                {
                    unset($items[$i]['items']);
                    if(!isset($item['url']))
                    {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
			if(!isset($item['active']))
            {
                if($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item,$route))
                    $active=$items[$i]['active']=true;
                else
                    $items[$i]['active']=false;
            }
            elseif($item['active'])
                $active=true;
		}
        return array_values($items);
    }

}
