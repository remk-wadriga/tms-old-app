<?php

/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 31.01.16
 * Time: 23:28
 */

Yii::import('zii.widgets.CListView');
class CustomListView extends CListView
{
    public function init()
    {
        parent::init();

    }


    public function renderPager()
    {
        if(!isset($this->pager['pages'])&&!$this->enablePagination)
            return;


        $pager=array();
        $class='CLinkPager';
        if(is_string($this->pager))
            $class=$this->pager;
        elseif(is_array($this->pager))
        {
            $pager=$this->pager;
            if(isset($pager['class']))
            {
                $class=$pager['class'];
                unset($pager['class']);
            }
        }
        if (!isset($this->pager['pages']))
            $pager['pages']=$this->dataProvider->getPagination();
        else
            $pager['pages'] = $this->pager['pages'];

        if($pager['pages']->getPageCount()>1)
        {
            echo '<div class="'.$this->pagerCssClass.'">';
            $this->widget($class,$pager);
            echo '</div>';
        }
        else
            $this->widget($class,$pager);
    }

    /**
     * Renders the summary text.
     */
    public function renderSummary()
    {
        if(!isset($this->pager['pages'])||($count=$this->dataProvider->getItemCount())<=0)
            return;

        echo CHtml::openTag($this->summaryTagName, array('class'=>$this->summaryCssClass));
        if(isset($this->pager['pages'])||$this->enablePagination)
        {

            if (isset($this->pager['pages']))
                $pagination = $this->pager['pages'];
            else
                $pagination=$this->dataProvider->getPagination();
            $total=$pagination->itemCount;
            $start=$pagination->currentPage*$pagination->pageSize+1;
            $end=$start+$count-1;
            if($end>$total)
            {
                $end=$total;
                $start=$end-$count+1;
            }
            if(($summaryText=$this->summaryText)===null)
                $summaryText=Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total);
            echo strtr($summaryText,array(
                '{start}'=>$start,
                '{end}'=>$end,
                '{count}'=>$total,
                '{page}'=>$pagination->currentPage+1,
                '{pages}'=>$pagination->pageCount,
            ));
        }
        else
        {
            if(($summaryText=$this->summaryText)===null)
                $summaryText=Yii::t('zii','Total 1 result.|Total {count} results.',$count);
            echo strtr($summaryText,array(
                '{count}'=>$count,
                '{start}'=>1,
                '{end}'=>$count,
                '{page}'=>1,
                '{pages}'=>1,
            ));
        }
        echo CHtml::closeTag($this->summaryTagName);
    }
}