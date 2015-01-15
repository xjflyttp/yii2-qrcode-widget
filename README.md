yii2-qrcode-widget
=====================
http://sourceforge.net/projects/phpqrcode/

composer.json
-----
```json
"require": {
        "xj/yii2-qrcode-widget": "*"
},
```


Action:
----
```php
//remote
public function actions() {
    return [
        's-upload' => [
            'class' => \xj\uploadify\UploadAction::className(),
            'uploadBasePath' => '@frontend/web/upload', //file system path
            'uploadBaseUrl' => \common\helpers\Url::getWebUrlFrontend('upload'), //web path
            'csrf' => true,
            'format' => 'image/{yyyy}{mm}{dd}/{time}{rand:6}', //save format
            'validateOptions' => [
                'extensions' => ['jpg', 'png'],
                'maxSize' => 1 * 1024 * 1024, //file size
            ],
            'beforeValidate' => function($actionObject) {},
            'afterValidate' => function($actionObject) {},
            'beforeSave' => function($actionObject) {},
            'afterSave' => function($filename, $fullFilename, $actionObject) {
                //$filename; // image/yyyymmddtimerand.jpg
                //$fullFilename; // /var/www/htdocs/image/yyyymmddtimerand.jpg
                //$actionObject; // xj\uploadify\UploadAction instance
            },
        ],
    ];
}




```