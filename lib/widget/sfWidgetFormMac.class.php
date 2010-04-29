<?php


class sfWidgetFormMac extends sfWidgetForm {
    static $javascriptIncluded = false;


/*
    protected function configure($options = array(), $attributes = array()) {

    }
*/

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $default = array();
        $separator = sfConfig::get('app_sfnetworkwidgets_macseparator', '-');
        $nbToken = 6;
        if(is_array($value)) {
            $default = $value;
        } elseif(is_string($value)) {
            $splitted = explode($separator, $value);
            if($nbToken == count($splitted)) {
                $default = $splitted;
            }
        } else {
            // fill an empty array with blank values
            $default = array_fill(0, $nbToken, '');
        }
        // every mac fields
        $mac = array();
        for($i = 0; $i < $nbToken; $i++) {
            $mac[$i] = $this->renderMacWidget($name.'['.$i.']', $default[$i]);
        }
        $js = '';
        if(sfConfig::get('app_sfnetworkwidget_js', true) && !sfWidgetFormMac::$javascriptIncluded) {
            $js .= $this->includeJavascript();
        }
        return implode($separator, $mac).$js;
    }


    protected function renderMacWidget($name, $value = null, $options = array(), $attributes = array()) {
        $widget = new sfWidgetFormInputText($options, array_merge($attributes, array('size' => '1', 'maxlength' => 2, 'class' => 'ybWidget-Mac')));
        return $widget->render($name, $value);
    }

    /**
     * description
     *
     * @param void
     * @return void
     */
    protected function includeJavascript() {
        switch(sfConfig::get('app_sfnetworkwidgets_jslib', 'none')) {
            case 'none' :
            case 'jquery' :
            $js = <<< EOL
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery(".ybWidget-Mac").keyup(function() {
        currentInput = jQuery(this);
        if(2 == currentInput.val().length) {
            currentInput.next().focus();
        } else {
        }
    });
});
</script>
EOL;
                break;
            case 'mootools' :
                break;
            case 'prototype' :
                break;
            default :
                break;
        }
        sfWidgetFormMac::$javascriptIncluded = true;
        return $js;
    }

}


