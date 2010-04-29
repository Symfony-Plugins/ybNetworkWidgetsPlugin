<?php


/**
 * class comment
 *
 * @class
 */
class sfValidatorMac extends sfValidatorBase {

    /**
     * description
     *
     * @param void
     * @return void
     */
    protected function configure($options = array(), $messages = array()) {
        $this->addMessage('txt', '"%value%" n\'est pas une adresse mac valide');

        $this->addMessage('int', 'Le champ à l\'index %index% n\'est pas un entier valide');
        $this->addMessage('min', 'Le champ à l\'index %index% ne peut être plus petit que 0');
        $this->addMessage('max', 'Le champ à l\'index %index% ne peut être plus grand que 255');
        $this->addMessage('hex', 'Le champ à l\'index %index% n\'est pas un nombre hexa-décimal');
    }

    /**
     * description
     *
     * @param void
     * @return void
     */
    protected function doClean($value) {
        foreach($value as $index => $tokenHex) {
            /**
             * converting an hex to an int, to make comparaisons
             */
            $tokenHex = strToUpper(sprintf('%02s', $tokenHex));
            $tokenInt = (int) base_convert($tokenHex, 16, 10);
            var_dump(strToUpper(sprintf('%02s', base_convert($tokenInt, 10, 16))));
            /**
             * not an INT ?!
             */
            if(!is_int($tokenInt)) {
                throw new sfValidatorError($this, 'int', array('index' => $index + 1));
            /**
             * re-converting to hex, if not equal, then a value bigger than
             * FF was given
             * Converting each token to a valid hex représentation: 0 => 00, F => 0F
             */
            } elseif($tokenHex != ($value[$index] = strToUpper(sprintf('%02s', base_convert($tokenInt, 10, 16))))) {
                throw new sfValidatorError($this, 'hex', array('index' => $index + 1));

            /**
             * smaller than 0 ?
             */
            } elseif($tokenInt < 0) {
                throw new sfValidatorError($this, 'min', array('index' => $index + 1));
            /**
             * bigger than 255 ?!
             */
            } elseif($tokenInt > 255) {
                throw new sfValidatorError($this, 'max', array('index' => $index + 1));
            }
        }
        return implode(':', $value);
    }
};
