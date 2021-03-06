<?php

namespace common\models;

use Yii;
use common\models\LanguageText;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $code
 * @property integer $active
 * @property integer $is_default
 *
 * @property CategoriesAttributesText[] $categoriesAttributesTexts
 * @property CitiesText[] $citiesTexts
 * @property Countries[] $countries
 * @property LanguagesText[] $languagesTexts
 * @property RegionsText[] $regionsTexts
 */
class Language extends \yii\db\ActiveRecord
{
    public static $_allLanguages;

    const LANG_CODE_EN = 'en';
    const LANG_CODE_RU = 'ru-RU';
    const LANG_RU = 1;
    const LANG_EN = 2;

    public static function tableName()
    {
        return 'languages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['active', 'is_default'], 'integer'],
            [['code','techname'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'techname' => 'Techname',
            'code' => 'Code',
            'active' => 'Active',
            'is_default' => 'Is Default',
        ];
    }

    public function behaviors()
    {
            return [
                [
                    'class' => \backend\behaviors\SaveRelation::className(),
                    'relations' => ['text']
                ],
                [
                    'class' => \frontend\behaviors\Multilanguage::className(),
                    'relationName'  => 'text',
                    'relationClassName' => LanguageText::className(),
                ],
            ];
    }

    public static function find(){
        return new scopes\LanguageQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesAttributesTexts()
    {
        return $this->hasMany(CategoriesAttributesText::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCityTexts()
    {
        return $this->hasMany(CityText::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountries()
    {
        return $this->hasMany(Country::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getText()
    {
        return $this->hasOne(LanguageText::className(), ['languages_id' => 'id']);
    }

    public function getTexts()
    {
        return $this->hasMany(LanguageText::className(), ['languages_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionsTexts()
    {
        return $this->hasMany(RegionsText::className(), ['languages_id' => 'id']);
    }

    public static function getDefault(){
        return self::findOne([
            'is_default' => true
        ]);
    }

    public static function getAllLanguages($onlyactive = false){
        if (!self::$_allLanguages){
            self::$_allLanguages = self::find();

            if ($onlyactive) {
                self::$_allLanguages->andWhere(['active' => true]);
            }

            self::$_allLanguages = self::$_allLanguages->all();
        }

        return self::$_allLanguages;
    }

    public static function getId(){
        return self::find()->where(['code' => Yii::$app->language])->one()->id;
    }

    /**
     * @param array $keys
     * @return array(key => value, key => value....)
     */
    static function getAllAsArray($keys = ['id', 'techname']){
        $result = [];
        $languages = self::find()->where(['active' => true])->all();
        if(!empty($languages)){
            foreach($languages as $k => $lang){
                foreach($keys as $key){
                    if(isset($lang->{$key})) {
                        $result[$k][$key] = $lang->{$key};
                    }
                }
            }
        }
        return $result;
    }
}
