yii2-qrcode-widget
=====================

composer.json
-----
```json
"require": {
    "xj/yii2-qrcode-widget": "~1.1"
},
```

example:
-----
```php
use xj\qrcode\QRcode;
use xj\qrcode\widgets\Text;
use xj\qrcode\widgets\Email;
use xj\qrcode\widgets\Card;

//Widget create a QR Image Url //QR Created by Widget
Text::widget([
    'outputDir' => '@webroot/upload/qrcode',
    'outputDirWeb' => '@web/upload/qrcode',
    'ecLevel' => QRcode::QR_ECLEVEL_L,
    'text' => 'test',
    'size' => 6,
]);

//Widget create a Action URL //QR Create by Action
Text::widget([
    'actions' => ['site/qrcode'],
    'text' => 'aaaa@gmail.com',
    'size' => 3,
    'margin' => 4,
    'ecLevel' => QRcode::QR_ECLEVEL_L,
]);

//other type
//Create EMAIL
Email::widget([
    'email' => 'aaaa@gmail.com',
    'subject' => 'myMail',
    'body' => 'do something',
]);

//Create Card
Card::widget([
    'actions' => ['clientQrcode'],
    'name' => 'SB',
    'phone' => '1111111111111',
    //here jpeg file is only 40x40, grayscale, 50% quality! 
    'avatar' => '@webroot/avatar.jpg',
]);

//Create Sms
Smsphone::widget([
    'actions' => ['clientQrcode'],
    'phone' => '131111111111',
]);
//Create Tel
Telphone::widget([
    'actions' => ['clientQrcode'],
    'phone' => '131111111111',
]);

```

// For Widget + Actions// the Action Parts
// Below 2 pars is  Blacklist and Whilelist, choose one are ok.
----
Action:
----
```php
// for the black list
public function actions() {
    return [
        //deny widget set size & margin & ecLevel
        'qrcode' => [
            'class' => QRcodeAction::className(),
            'enableCache' => false,
            //
            'allowClientEclevel' => false,
            'ecLevel' => QRcode::QR_ECLEVEL_H,
            //
            'defaultSize' => 4,
            'allowClientSize' => false,
            //
            'defaultMargin' => 2,
            'allowClientMargin' => false,
        ]
    ];
}



```php
// for the while list
public function actions() {
    return [
        //allow widget set size & margin & ecLevel
        'qrcode' => [
            'class' => QRcodeAction::className(),
            //you can disable cache
            'enableCache' => true,
            //
            'allowClientEclevel' => true,
            'ecLevel' => QRcode::QR_ECLEVEL_H,
            //
            'defaultSize' => 4,
            'allowClientSize' => true,
            'maxSize' => 10,
            //
            'defaultMargin' => 2,
            'allowClientMargin' => true,
            'maxMargin' => 10,
            'outputDir' => '@webroot/upload/qrcode',

            //closure, you can ignore this selection.
            'onGetFilename' => function (QRcodeAction $data) {
                /* @var $data QRcodeAction */
                //dosomething
                return sha1($data->text) . '.png';
            }
        ]
    ];
}
```
