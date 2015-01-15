<?php

namespace xj\qrcode\widgets;

use Yii;
use xj\qrcode\widgets\Text;
use xj\qrcode\formatter\Card as FormatCard;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class Card extends Text {

    public $name;
    public $phone;

    /**
     * must be jpg
     * @var string
     */
    public $avatar;

    public function run() {
        if (false !== empty($this->avatar) && strtolower(pathinfo($this->avatar, PATHINFO_EXTENSION)) != 'jpg') {
            //'avatar filetype must be jpg'
            $this->avatar = null;
        } else {
            $this->avatar = Yii::getAlias($this->avatar);
            if (!file_exists($this->avatar)) {
                $this->avatar = null;
            }
        }

        $formatter = new FormatCard([
            'name' => $this->name,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
        ]);
        $this->text = $formatter->format();
        return parent::run();
    }

}
