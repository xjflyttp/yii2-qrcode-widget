<?php

namespace xj\qrcode;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'qrlib.php';

use Yii;
use yii\helpers\ArrayHelper;

class QRcode extends \QRcode {

    // Encoding modes
    const QR_MODE_NUL = QR_MODE_NUL;
    const QR_MODE_NUM = QR_MODE_NUM;
    const QR_MODE_AN = QR_MODE_AN;
    const QR_MODE_8 = QR_MODE_8;
    const QR_MODE_KANJI = QR_MODE_KANJI;
    const QR_MODE_STRUCTURE = QR_MODE_STRUCTURE;
    // Levels of error correction.
    const QR_ECLEVEL_L = QR_ECLEVEL_L;
    const QR_ECLEVEL_M = QR_ECLEVEL_M;
    const QR_ECLEVEL_Q = QR_ECLEVEL_Q;
    const QR_ECLEVEL_H = QR_ECLEVEL_H;
    // Supported output formats
    const QR_FORMAT_TEXT = QR_FORMAT_TEXT;
    const QR_FORMAT_PNG = QR_FORMAT_PNG;

    /**
     * status for init
     * @var bool
     */
    public static $isInit = false;

    /**
     * 
     * @return []
     */
    public static function getEclevelOptions() {
        return [
            static::QR_ECLEVEL_H,
            static::QR_ECLEVEL_Q,
            static::QR_ECLEVEL_M,
            static::QR_ECLEVEL_L,
        ];
    }

    /**
     * init qrcode
     * @param [] $options
     * @return bool
     */
    public static function init($options = []) {
        if (static::$isInit === true) {
            return static::$isInit;
        }
        $defaultOptions = [
            'QR_CACHEABLE' => true,
            'QR_CACHE_DIR' => Yii::getAlias('@runtime/cache/qrcode') . DIRECTORY_SEPARATOR,
            'QR_LOG_DIR' => Yii::getAlias('@runtime/logs/qrcode') . DIRECTORY_SEPARATOR,
            'QR_FIND_BEST_MASK' => true, // if true=>estimates best mask (spec. default=>but extremally slow; set to false to significant performance boost but (propably) worst quality code
            'QR_FIND_FROM_RANDOM' => false, // if false=>checks all masks available=>otherwise value tells count of masks need to be checked=>mask id are got randomly
            'QR_DEFAULT_MASK' => 2, // when QR_FIND_BEST_MASK === false
            'QR_PNG_MAXIMUM_SIZE' => 1024, // maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images
        ];
        $options = ArrayHelper::merge($defaultOptions, $options);
        foreach ($options as $key => $val) {
            define($key, $val);
        }
        //mkdir
        if (false === file_exists($defaultOptions['QR_CACHE_DIR'])) {
            @mkdir($defaultOptions['QR_CACHE_DIR'], 0775, true);
        }
        if (false === file_exists($defaultOptions['QR_LOG_DIR'])) {
            @mkdir($defaultOptions['QR_LOG_DIR'], 0775, true);
        }

        //reset status
        return static::$isInit = true;
    }

}
