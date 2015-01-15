<?php

namespace xj\qrcode\actions;

use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\HttpException;
use xj\qrcode\QRcode;

/**
 * @author xjflyttp <xjflyttp@gmail.com>
 * @see http://phpqrcode.sourceforge.net/docs/html/class_q_rcode.html#a1b90c0989105afa06b6e1c718a454fb5
 */
class QRcodeAction extends Action {

    /**
     * FileCache
     * @var bool
     */
    public $enableCache = false;

    /**
     *
     * @var bool
     */
    public $allowClientEclevel = false;

    /**
     * error correction level QR_ECLEVEL_L, QR_ECLEVEL_M, QR_ECLEVEL_Q or QR_ECLEVEL_H
     * @var int Const
     */
    public $ecLevel = QRcode::QR_ECLEVEL_L;

    /**
     *
     * @var bool
     */
    public $allowClientSize = false;

    /**
     * pixel size, multiplier for each 'virtual' pixel 
     * @var int [1234] 
     */
    public $defaultSize = 3;

    /**
     * only for remote
     * @var int 
     */
    public $maxSize = 3;

    /**
     *
     * @var bool
     */
    public $allowClientMargin = false;

    /**
     * code margin (silent zone) in 'virtual' pixels 
     * @var int
     */
    public $defaultMargin = 4;

    /**
     * only for remote
     * @var int 
     */
    public $maxMargin = 4;

    /**
     * function to getFilename
     * @params $text
     * @params $size
     * @params $margin
     * @params $ecLevel 
     * @params $initOptions
     * @var closure
     */
    public $onGetFilename = null;

    /**
     *
     * @var string
     */
    public $outputDir = null;

    /**
     *
     * @var string
     */
    public $text = null;

    /**
     * init options
     * @var [] 
     * @see xj\qrcode\QRcode defaultOptions
     */
    public $options = [
            // if true=>estimates best mask (spec. default=>but extremally slow; set to false to significant performance boost but (propably) worst quality code
//        'QR_FIND_BEST_MASK' => true,
            // if false=>checks all masks available=>otherwise value tells count of masks need to be checked=>mask id are got randomly
//        'QR_FIND_FROM_RANDOM' => false,
            // maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images
//        'QR_PNG_MAXIMUM_SIZE' => 1024,
    ];

    public function run($text) {
        $size = $this->defaultSize;
        $margin = $this->defaultMargin;
        $ecLevel = $this->ecLevel;
        $this->text = $text;

        if (empty($text)) {
            throw new HttpException(404, 'QR is not exist.');
        }

        if (true === $this->allowClientSize) {
            $clientSize = intval(Yii::$app->request->get('size', $this->defaultSize));
            if ($clientSize >= $this->defaultSize && $clientSize <= $this->maxSize) {
                $this->defaultSize = $clientSize;
            }
        }

        if (true === $this->allowClientMargin) {
            $clientMargin = intval(Yii::$app->request->get('margin', $this->defaultMargin));
            if ($clientMargin >= $this->defaultMargin && $clientMargin <= $this->maxMargin) {
                $this->defaultMargin = $clientMargin;
            }
        }

        if (true === $this->allowClientEclevel) {
            $clientEclevel = intval(Yii::$app->request->get('ec', $this->ecLevel));
            $ecOptions = QRcode::getEclevelOptions();
            if (in_array($clientEclevel, $ecOptions)) {
                $this->ecLevel = $clientEclevel;
            }
        }

        if ($this->outputDir === null) {
            $outputfile = null;
        } elseif (is_callable($this->onGetFilename)) {
            $outputfile = $this->getOutputDir() . DIRECTORY_SEPARATOR . call_user_func_array($this->onGetFilename, [$this]);
        } else {
            $outputfile = $this->getOutputDir() . DIRECTORY_SEPARATOR . $this->getFilename();
        }

        QRcode::init(ArrayHelper::merge($this->options, [
                    'QR_CACHEABLE' => $this->enableCache,
        ]));

        header('Content-Type: image/png');
        QRcode::png($text, $outputfile, $this->ecLevel, $this->defaultSize, $this->defaultMargin, true);
    }

    /**
     * 
     * @return string
     */
    protected function getOutputDir() {
        return Yii::getAlias($this->outputDir);
    }

    /**
     * Hash by $this->text
     * @return string
     * @throws \yii\base\Exception
     */
    protected function getFilename() {
        $outputDir = $this->getOutputDir();
        $hash = sha1($this->getHashMetaData());
        $filename = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash . '.png';
        $tmpPath = $outputDir . '/' . $filename;
        $tmpPathDir = dirname($tmpPath);
        if (false === file_exists($tmpPathDir)) {
            @mkdir($tmpPathDir, 0775, true);
        }
        return $filename;
    }

    /**
     * provider to hash filename
     * @return string
     */
    protected function getHashMetaData() {
        $options = $this->options;
        if (isset($options['QR_CACHEABLE'])) {
            unset($options['QR_CACHEABLE']);
        }
        return Json::encode([
                    $this->text,
                    $this->defaultSize,
                    $this->defaultMargin,
                    $this->ecLevel,
                    $options,
        ]);
    }

}
