<?php


/**
 * class comment
 *
 * @class
 */
class sfValidatorIp extends sfValidatorBase {

    /**
     * description
     *
     * @param void
     * @return void
     */
    protected function configure($options = array(), $messages = array()) {
        $this->addMessage('txt', '"%value%" n\'est pas une adresse mac valide');

        $this->addMessage('int', 'Tous les champs doivent être des entiers');
        $this->addMessage('min', 'Un champ ne peut pas être plus petit que 0');
        $this->addMessage('max', 'Un champ ne peut pas être plus grand que 255');
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
            }
            $value[$index] = (string) $token;
        }
        return implode('.', $value);
    }
};
