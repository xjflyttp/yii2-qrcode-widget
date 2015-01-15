<?php

namespace xj\qrcode\widgets;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use xj\qrcode\formatter\Email as FormatEmail;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class Email extends Text {

    public $email;
    public $subject;
    public $body;

    public function run() {
        $formatter = new FormatEmail([
            'email' => $this->email,
            'subject' => $this->subject,
            'body' => $this->body,
        ]);
        $this->text = $formatter->format();
        return parent::run();
    }

}
