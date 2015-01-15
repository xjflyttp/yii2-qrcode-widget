<?php

namespace xj\qrcode\formatter;

use yii\base\Object;
use xj\qrcode\formatter\IFormatter;

class Telphone extends Object implements IFormatter {

    /**
     *
     * @var string PhoneNumber
     */
    public $phone;

    /**
     * 
     * @return string
     */
    public function format() {
        return 'tel:' . $this->phone;
    }

}
