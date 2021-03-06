<?php
namespace common\models;

use common\models\libraries\TelegrammLoging;
use Exception;
use frontend\components\Location;
use Yii;
use yii\helpers\Url;

class Mailer {

    /**
     * @param $send_to, адресат
     * @param $subject
     * @param $template, представление (common/mail)
     * @param $arr, ассоциативный массив с параметрами
     * @param $from, ассоциативный массив ['email' => 'example@mail.ru', 'name' => 'example_name']
     * @param $attachement, array <File>
     */
    public static function send($send_to, $subject, $template, $arr){
        $url = Url::base(true);
        switch(Location::getCurrentDomain()){
            case "obiavo.ru" :
                $from_arr = ['robot@obiavo.ru' => Yii::$app->name];
                break;
            case "obiavo.by" :
                $from_arr = ['robot@obiavo.by' => Yii::$app->name];
                break;
            case "obiavo.kz" :
                $from_arr = ['robot@obiavo.kz' => Yii::$app->name];
                break;
            case "obiavo.uz" :
                $from_arr = ['robot@obiavo.uz' => Yii::$app->name];
                break;
            case "obiavo.su" :
                $from_arr = ['robot@obiavo.su' => Yii::$app->name];
                break;
            case "obiavo.com" :
                $from_arr = ['robot@obiavo.com' => Yii::$app->name];
                break;
            case "obiavo.co.uk" :
                $from_arr = ['robot@obiavo.co.uk' => Yii::$app->name];
                break;
            case "obiavo.com.ng" :
                $from_arr = ['robot@obiavo.com.ng' => Yii::$app->name];
                break;
            case "obiavo.in" :
                $from_arr = ['robot@obiavo.in' => Yii::$app->name];
                break;
            case "obiavo.net" :
                $from_arr = ['robot@obiavo.net' => Yii::$app->name];
                break;
        }
        // если мы на тестовом серваке, то отпрвитель должен быть robot@obiavo.site
        if($url == "http://obiavo.site" OR $url == "obiavodev.ru"){
            $from_arr = ['robot@obiavo.site' => Yii::$app->name];
        }
        Yii::$app
            ->mailer
            ->compose(
                ['html' => $template],
                $arr
            )
            ->setFrom($from_arr)
            ->setTo($send_to)
            ->setSubject($subject)
            ->send();

    }
}