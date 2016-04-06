<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.06.2015
 * Time: 19:03
 */
class CustomRadioButtonList extends CHtml
{
    /**
     * Generates a radio button list.
     * A radio button list is like a {@link checkBoxList check box list}, except that
     * it only allows single selection.
     * @param string $name name of the radio button list. You can use this name to retrieve
     * the selected value(s) once the form is submitted.
     * @param string $select selection of the radio buttons.
     * @param array $data value-label pairs used to generate the radio button list.
     * Note, the values will be automatically HTML-encoded, while the labels will not.
     * @param array $htmlOptions additional HTML options. The options will be applied to
     * each radio button input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each radio button is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * radio button input tag while "{label}" will be replaced by the corresponding radio button label,
     * {beginLabel} will be replaced by &lt;label&gt; with labelOptions, {labelTitle} will be replaced
     * by the corresponding radio button label title and {endLabel} will be replaced by &lt;/label&gt;</li>
     * <li>separator: string, specifies the string that separates the generated radio buttons. Defaults to new line (<br/>).</li>
     * <li>labelOptions: array, specifies the additional HTML attributes to be rendered
     * for every label tag in the list.</li>
     * <li>container: string, specifies the radio buttons enclosing tag. Defaults to 'span'.
     * If the value is an empty string, no enclosing tag will be generated</li>
     * <li>baseID: string, specifies the base ID prefix to be used for radio buttons in the list.
     * This option is available since version 1.1.13.</li>
     * <li>empty: string, specifies the text corresponding to empty selection. Its value is empty.
     * The 'empty' option can also be an array of value-label pairs.
     * Each pair will be used to render a radio button at the beginning. Note, the text label will NOT be HTML-encoded.
     * This option is available since version 1.1.14.</li>
     * </ul>
     * @return string the generated radio button list
     */
    public static function radioButtonList($name,$select,$data,$htmlOptions=array())
    {
        $template=isset($htmlOptions['template'])?$htmlOptions['template']:'{input} {label}';
        $separator=isset($htmlOptions['separator'])?$htmlOptions['separator']:"";
        $container=isset($htmlOptions['container'])?$htmlOptions['container']:'div';
        unset($htmlOptions['template'],$htmlOptions['separator'],$htmlOptions['container']);

        $labelOptions=isset($htmlOptions['labelOptions'])?$htmlOptions['labelOptions']:array('style'=>'display:inline-block;margin-right:10px;');
        unset($htmlOptions['labelOptions']);

        if(isset($htmlOptions['empty']))
        {
            if(!is_array($htmlOptions['empty']))
                $htmlOptions['empty']=array(''=>$htmlOptions['empty']);
            $data=array_merge($htmlOptions['empty'],$data);
            unset($htmlOptions['empty']);
        }

        $items=array();
        $baseID=isset($htmlOptions['baseID']) ? $htmlOptions['baseID'] : self::getIdByName($name);
        unset($htmlOptions['baseID']);
        $id=0;
        foreach($data as $value=>$labelTitle)
        {
            $checked=!strcmp($value,$select);
            $htmlOptions['value']=$value;
            $htmlOptions['id']=$baseID.'_'.$id++;
            $option=self::radioButton($name,$checked,$htmlOptions);
            $beginLabel=self::openTag('div',$labelOptions);
            $label=self::label($labelTitle,$htmlOptions['id'],$labelOptions);
            $endLabel=self::closeTag('div');
            $items[]=strtr($template,array(
                '{beginLabel}'=>$beginLabel,
                '{input}'=>$option,
                '{label}'=>$label,
                '{endLabel}'=>$endLabel,
            ));
        }
        if(empty($container))
            return implode($separator,$items);
        else
            return self::tag($container,array('id'=>$baseID),implode($separator,$items));
    }
}