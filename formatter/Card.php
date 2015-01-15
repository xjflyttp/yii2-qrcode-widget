<?php

namespace xj\qrcode\formatter;

use yii\base\Object;
use xj\qrcode\formatter\IFormatter;

class Card extends Object implements IFormatter {

    /**
     * Card Username
     * @var string
     */
    public $name;

    /**
     * PhoneNumber
     * @var string 
     */
    public $phone;

    /**
     * JPG File Path
     * @var string 
     */
    public $avatar;

    /**
     * 
     * @return string
     */
    public function format() {
        $content = 'BEGIN:VCARD' . "\n";
        $content .= 'FN:' . $this->name . "\n";
        $content .= 'TEL;WORK;VOICE:' . $this->phone . "\n";
        if (!empty($this->avatar) && file_exists($this->avatar)) {
            $content .= 'PHOTO;JPEG;ENCODING=BASE64:' . base64_encode(file_get_contents($this->avatar)) . "\n";
        }
        $content .= 'END:VCARD';
        return $content;
    }

}
