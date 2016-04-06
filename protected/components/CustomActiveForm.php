<?php
/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 06.02.15
 * Time: 15:13
 */

Yii::import('booster.widgets.TbActiveForm');

class CustomActiveForm extends TbActiveForm {
    protected function verticalGroup(&$fieldData, &$model, &$attribute, &$options) {

        $groupOptions = isset($options['groupOptions']) ? $options['groupOptions']: array();
        self::addCssClass($groupOptions, 'form-group');

        $_attribute = $attribute;
        CHtml::resolveName($model, $_attribute);
        if ($model->hasErrors($_attribute))
            self::addCssClass($groupOptions, 'has-error');

        echo CHtml::openTag('div', $groupOptions);

        self::addCssClass($options['labelOptions'], 'control-label');
        if (isset($options['label'])) {
            if (!empty($options['label'])) {
                echo CHtml::label($options['label'], CHtml::activeId($model, $attribute), $options['labelOptions']);
            }
        } else {
            echo $this->labelEx($model, $attribute, $options['labelOptions']);
        }

        if ($this->showErrors && $options['errorOptions'] !== false) {
            echo $this->error($model, $attribute, $options['errorOptions'], $options['enableAjaxValidation'], $options['enableClientValidation']);
        }

        if(isset($options['wrapperHtmlOptions']) && !empty($options['wrapperHtmlOptions']))
            $wrapperHtmlOptions = $options['wrapperHtmlOptions'];
        else
            $wrapperHtmlOptions = $options['wrapperHtmlOptions'] = array();
        echo CHtml::openTag('div', $wrapperHtmlOptions);

        if (!empty($options['prepend']) || !empty($options['append'])) {
            $this->renderAddOnBegin($options['prepend'], $options['append'], $options['prependOptions']);
        }

        if (is_array($fieldData)) {
            echo call_user_func_array($fieldData[0], $fieldData[1]);
        } else {
            echo $fieldData;
        }

        if (!empty($options['prepend']) || !empty($options['append'])) {
            $this->renderAddOnEnd($options['append'], $options['appendOptions']);
        }

        if (isset($options['hint'])) {
            self::addCssClass($options['hintOptions'], $this->hintCssClass);
            echo CHtml::tag($this->hintTag, $options['hintOptions'], $options['hint']);
        }

        echo '</div></div>';
    }
}