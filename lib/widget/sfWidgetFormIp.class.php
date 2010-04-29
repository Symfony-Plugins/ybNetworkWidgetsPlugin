<?php

/**
 * class comment
 *
 * @class
 */
class sfWidgetFormIP extends sfWidgetForm {
    static $javascriptIncluded = false;
    /**
     * description
     *
     * @param void
     * @return void
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $default = array();
        $separator = '.'; //sfConfig::get('app_sfnetworkwidgets_macseparator', '-');
        $nbToken = 4;
        if(is_array($value)) {
            $default = $value;
        } elseif(is_string($value)) {
            $splitted = explode($separator, $value);
            if($nbToken == count($splitted)) {
                $default = $splitted;
            }
        } else {
            $default = array_fill(0, $nbToken, '');
        }
        $ip = array();
        for($i = 0; $i < $nbToken; $i++) {
            $ip[$i] = $this->renderIpWidget($name.'['.$i.']', $default[$i]);
        }
        $js = '';
        if(!sfWidgetFormIP::$javascriptIncluded) {
            $js .= $this->includeJavascript();
        }
        return implode($separator, $ip).$js;
    }

    /**
      * description
      *
      * @param void
      * @return void
      */
    protected function renderIpWidget($name, $value = null, $options = array(), $attributes = array()) {
        $widget = new sfWidgetFormInputText($options, array_merge($attributes, array('size' => '2', 'maxlength' => 3, 'class' => 'ybWidget-IP')));
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
    jQuery(".ybWidget-IP").keyup(function(event) {
        if(16 == event.keyCode || 9 == event.keyCode) { // shift or tab key
            return;
        }
        currentInput = jQuery(this);
        currentInputVal = currentInput.val()
        if(110 == event.keyCode) { // dot
            // removing the trailing dot
            currentInput.val(parseInt(currentInputVal));
            // changing focus
            currentInput.next().focus();
            return;
        }
        /**
         * hit the max string length
         * or if the value is bigger than 25, changing, because we
         * can have 26x+ values
         */
        if(3 == currentInputVal.length || 25 < currentInputVal) {
            // change focus
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

