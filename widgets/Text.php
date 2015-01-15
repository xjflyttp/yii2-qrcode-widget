<?php

namespace xj\qrcode\widgets;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\base\Widget;
use yii\base\Exception;
use yii\helpers\Json;
use xj\qrcode\QRcode;

class Text extends Widget {

    /**
     * URL to QrcodeAction
     * @var [] 
     */
    public $actions = ['qrcode'];

    /**
     * if has outputDir, will ignore actions
     * @var string
     */
    public $outputDir = null;

    /**
     * output in web url
     * @var string
     */
    public $outputDirWeb = null;

    /**
     * QRcode init Options
     * only for has $outfile
     * @var []
     */
    public $qrOptions = [];

    /**
     * QRcode Ec Level
     * @var int
     */
    public $ecLevel = QRcode::QR_ECLEVEL_L;

    /**
     * 
     * @var string
     */
    public $text = '';

    /**
     * img Options
     * @var []
     */
    public $imgOptions = [];

    /**
     *
     * @var int
     */
    public $size = null;

    /**
     *
     * @var int
     */
    public $margin = null;

    /**
     * output <img src="qrcode/s/content" />
     */
    public function run() {
        echo $this->getContent();
    }

    protected function getContent() {
        return empty($this->outputDir) || empty($this->outputDirWeb) ? $this->getRemote() : $this->getLocal();
    }

    protected function getLocal() {
        $outputDir = $this->getOutputDir();
        $filename = $this->getFilename();
        $saveFilename = $outputDir . '/' . $filename;
        $webFilename = $this->getOutputDirWeb() . '/' . $filename;
        if (false === file_exists($saveFilename)) {
            QRcode::init($this->qrOptions);
            //save file
            QRcode::png($this->text, $saveFilename, $this->ecLevel, $this->size, $this->margin);
        }
        //render img tag
        return Html::img($webFilename, $this->imgOptions);
    }

    /**
     * 
     * @return string
     */
    protected function getOutputDir() {
        return Yii::getAlias($this->outputDir);
    }

    /**
     * 
     * @return string
     */
    protected function getOutputDirWeb() {
        return Yii::getAlias($this->outputDirWeb);
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
        $options = $this->qrOptions;
        if (isset($options['QR_CACHEABLE'])) {
            unset($options['QR_CACHEABLE']);
        }
        return Json::encode([
                    $this->text,
                    $this->size,
                    $this->margin,
                    $this->ecLevel,
                    $options,
        ]);
    }

    protected function getRemote() {
        $action = $this->actions;
        $action['text'] = $this->text;
        if ($this->size !== null) {
            $action['size'] = $this->size;
        }
        if ($this->margin !== null) {
            $action['margin'] = $this->margin;
        }
        if ($this->ecLevel !== null) {
            $action['ec'] = $this->ecLevel;
        }
        return Html::img($action, $this->imgOptions);
    }

}
