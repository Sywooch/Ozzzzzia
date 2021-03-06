<?php

namespace common\models;

use common\models\scopes\CmsQuery;
use frontend\components\Location;
use Yii;

/**
 * This is the model class for table "cms".
 *
 * @property integer $id
 * @property string $techname
 *
 * @property  CmsText[] $cmsTexts
 */
class Cms extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['techname'], 'required'],
            [['techname'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'techname' => __('Tech name'),
        ];
    }

    public static function find(){
        return new CmsQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            [
                'class' => \backend\behaviors\SaveRelation::className(),
                'relations' => ['cmsText']
            ],
            [
                'class' => \frontend\behaviors\Multilanguage::className(),
                'relationName' => 'cmsText',
                'relationClassName' => CmsText::className(),
            ],
        ];
    }

    public function transactions() {
        return [
            // scenario name => operation (insert, update or delete)
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsText()
    {
        return $this->hasOne(CmsText::className(), ['cms_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsTexts()
    {
        return $this->hasMany(CmsText::className(), ['cms_id' => 'id']);
    }

    /**
     * @param $techname
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getByTechname($techname){
        $cms_page = Cms::find()
            ->leftJoin('cms_text', '`cms_text`.`cms_id` = `cms`.`id`')
            ->where(['techname' => $techname, 'cms_text.languages_id' => Yii::$app->location->country->localLanguage->id])
            ->one();
        if(!$cms_page){
            $cms_page = Cms::find()
                ->leftJoin('cms_text', '`cms_text`.`cms_id` = `cms`.`id`')
                ->where(['techname' => $techname, 'cms_text.languages_id' => Location::getDefaultLanguageId()])
                ->one();
        }
        $cms_id = $cms_page->id;
        $cms_text = CmsText::find()->where(['cms_id'=>$cms_id, "languages_id" => Yii::$app->location->country->localLanguage->id])->one();
        if(!$cms_text){
            $cms_text = CmsText::find()->where(['cms_id'=>$cms_id, "languages_id" => Location::getDefaultLanguageId()])->one();
        }
        $cms_page->_text = $cms_text;
        return $cms_page;
    }
}
