<?php
namespace frontend\controllers;

use common\models\CmsText;
use common\models\Country;
use common\models\Language;
use frontend\components\Location;
use Yii;
use common\models\Cms;

class CmsController extends BaseController
{
    public $params;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionView(){
        $cms_url = Yii::$app->request->get('cmsUrl');
        $domain = Location::getCurrentDomain();
        $country = Country::find()->where(["domain"=>$domain])->one();
        $cms_id = Cms::find()
            ->leftJoin('cms_text', '`cms_text`.`cms_id` = `cms`.`id`')
            ->where(['cms_text.url' => $cms_url])
            ->one()->id;
        $cms_text = CmsText::find()->where(['cms_id'=>$cms_id, "languages_id" => $country->languages_id])->one();
        if(!$cms_text){
            $default_language = Language::find()->where(['is_default'=>1])->one();
            $cms_text = CmsText::find()->where(['cms_id'=>$cms_id, "languages_id" => $default_language->id])->one();
        }
        $this->setPageTitle($cms_text->seo_title);
        $breadcrumbs = [['label' => $cms_text->seo_title, 'link' => $cms_text->url, 'use_cookie' => true]];
        Yii::$app->view->params['breadcrumbs'] = $this->setBreadcrumbs($breadcrumbs);
        Yii::$app->view->params['seo_h1'] = $cms_text->seo_h1;
        Yii::$app->view->params['seo_desc'] = $cms_text->seo_desc;
        Yii::$app->view->params['seo_keywords'] = $cms_text->seo_keywords;

        return $this->render('view', [
            'page'   => $cms_text
        ]);
    }
}