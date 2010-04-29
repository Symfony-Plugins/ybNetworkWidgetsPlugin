<?php


/**
 * class comment
 *
 * @class
 */
class sfValidatorCidr extends sfValidatorIp {

    /**
     * description
     *
     * @param void
     * @return void
     */
    protected function configure($options = array(), $messages = array()) {
        parent::configure($options, $messages);
        $this->addMessage('maskMax', 'Un masque de réseau ne peut être supérieur à 32');
    }

    /**
     * description
     *
     * @param void
     * @return void
     */
    protected function doClean($value) {
        foreach($value as $index => $token) {
            $token = (int) $token;
            if(!is_int($token)) {
                throw new sfValidatorError($this, 'int', array('value' => $value));
            } elseif($token < 0) {
                throw new sfValidatorError($this, 'min', array('value' => $value));
            } elseif($token > 255) {
                throw new sfValidatorError($this, 'max', array('value' => $value));
            } elseif(4 == $index && 32 < $token) {
                throw new sfValidatorError($this, 'maskMax', array('value' => $value));
            }
            $value[$index] = (string) $token;
        }
        $mask = array_pop($value);
        return implode('.', $value) . '/' . $mask;
    }
};
