<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "add_application_text".
 *
 * @property integer $id
 * @property integer $add_application_id
 * @property integer $languages_id
 * @property string $url
 * @property string $seo_title
 * @property string $seo_h1
 * @property string $seo_h2
 * @property string $seo_desc
 * @property string $seo_keywords
 * @property string $seo_text
 * @property string $seo_text1
 * @property string $seo_text2
 * @property string $seo_text3
 * @property string $seo_text4
 * @property string $seo_text5
 * @property string $seo_text6
 * @property string $seo_text7
 *
 * @property AddApplication $add_application
 * @property Language $languages
 */
class AddApplicationText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'add_application_text';
    }
    const SCENARIO_DEFAULT = 'default';

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'add_application_id',
                'languages_id',
                'seo_title',
                'url',
                'seo_text',
                'seo_text1',
                'seo_text2',
                'seo_text3',
                'seo_text4',
                'seo_text5',
                'seo_text6',
                'seo_text7',
                'seo_h1',
                'seo_h2',
                'seo_desc',
                'seo_keywords'
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_application_id', 'seo_title', 'url'], 'required'],
            [['add_application_id', 'languages_id'], 'integer'],
            [['seo_title'], 'string', 'max' => 255],
            [['add_application_id'], 'exist', 'skipOnError' => true, 'targetClass' => AddApplication::className(), 'targetAttribute' => ['add_application_id' => 'id']],
            [['languages_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['languages_id' => 'id']],
            [['languages_id'],'default', 'value' => Language::getDefault()->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'add_application_id' => 'Add application ID',
            'languages_id' => 'Languages ID',
            'url' => 'URL',
            'seo_title' => 'Title',
            'seo_text' => 'Text',
            'seo_h1' => 'H1',
            'seo_h2' => 'H2',
            'seo_desc' => 'Description',
            'seo_keywords' => 'Keywords',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddApplication()
    {
        return $this->hasOne(AddApplication::className(), ['id' => 'add_application_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languages_id']);
    }
}
