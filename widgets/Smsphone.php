<?php

namespace xj\qrcode\widgets;

use Yii;
use xj\qrcode\widgets\Text;
use xj\qrcode\formatter\Smsphone as FormatSms;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class Smsphone extends Text {

    public $phone;

    public function run() {

        $formatter = new FormatSms([
            'phone' => $this->phone,
        ]);
        $this->text = $formatter->format();
        return parent::run();
    }

}
