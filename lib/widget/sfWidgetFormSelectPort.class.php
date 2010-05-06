<?php



/**
 * class comment
 *
 * @class
 */
class sfWidgetFormSelectPort extends sfWidgetFormSelect {


    /**
     * description
     *
     * @param void
     * @return void
     */
    public function __construct($options = array(), $attributes = array()) {
        $choices = $this->getPortsChoices();
        $options = array_merge($options, array('choices' => $choices));
        parent::__construct($options, $attributes);
    }

    /**
     * description
     *
     * @param void
     * @return void
     */
    public function getPortsChoices() {
        $services = '/etc/services';
        $ports = array();
        if(file_exists($services) && is_readable($services)) {
            $content = file($services);
            foreach($content as $line) {
                $line = preg_replace('/\s{1,}/S', ' ', trim($line));
                /**
                 * strip out blank lines
                 */
                if('' == $line) {
                    continue;
                }
                /**
                 * strip out comments
                 */
                if('#' == $line[0]) {
                    continue;
                }
                $portname = '';
                $port = '';
                $protocol = '';
                $description = '';
                /**
                 * ok, we have a valid line
                 */
                $data = explode(" ", $line);
                //var_dump($data);
                if(!isset($data[0])) {
                    continue;
                }
                $portname = $data[0];
                do {
                    $last = array_shift($data);
                } while('' == $last);
                //list($port, $protocol) = explode('/', trim(array_shift($data)));
                $portProtocol = explode('/', trim(array_shift($data)));
                switch(count($portProtocol)) {
                    case 0 :
                        $port = $protocol = null;
                        break;
                    case 1 :
                        $port = $portProtocol[0];
                        $protocol = null;
                        break;
                    case 2 :
                        list($port, $protocol) = $portProtocol;
                        break;
                }
                if(!count($data)) {
                    //$protocols[] = array($protoname, $port, $protocol, '');
                    $ports[$portname . '_' . $protocol] = $port . '/' . $protocol;
                    continue;
                }
                $description = ltrim(implode(' ', $data), '# ');
                $ports[$portname . '_' . $protocol] = $port . '/' . $protocol . ' - ' . $description;

            }
        }
        //var_dump($protocols);
        natsort($ports);
        return $ports;
    }


};
