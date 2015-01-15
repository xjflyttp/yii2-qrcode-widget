<?php

namespace xj\qrcode\widgets;

use Yii;
use xj\qrcode\widgets\Text;
use xj\qrcode\formatter\Telphone as FormatTel;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class Telphone extends Text {

    public $phone;

    public function run() {

        $formatter = new FormatTel([
            'phone' => $this->phone,
        ]);
        $this->text = $formatter->format();
        return parent::run();
    }

}
