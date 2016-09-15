<?php

namespace DotPlant\Store\models\order;

use DevGroup\Entity\traits\EntityTrait;
use DevGroup\Entity\traits\SoftDeleteTrait;
use DevGroup\Multilingual\behaviors\MultilingualActiveRecord;
use DevGroup\Multilingual\traits\MultilingualTrait;
use DotPlant\Store\components\MultilingualListDataQuery;
use Yii;

/**
 * This is the model class for table "{{%dotplant_store_delivery}}".
 *
 * @property integer $id
 * @property integer $context_id
 * @property string $handler_class_name
 * @property string $packed_json_handler_params
 * @property integer $sort_order
 * @property integer $is_active
 * @property integer $is_deleted
 */
class Delivery extends \yii\db\ActiveRecord
{
    use EntityTrait;
    use MultilingualTrait;
    use SoftDeleteTrait;

    public function behaviors()
    {
        return [
            'multilingual' => [
                'class' => MultilingualActiveRecord::class,
                'translationPublishedAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dotplant_store_delivery}}';
    }

    /**
     * @inheritdoc
     */
    public function getRules()
    {
        return [
            [['context_id'], 'required'],
            [['context_id', 'sort_order', 'is_active'], 'integer'],
            [['packed_json_handler_params'], 'string'],
            [['handler_class_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAttributeLabels()
    {
        return [
            'id' => Yii::t('dotplant.store', 'ID'),
            'context_id' => Yii::t('dotplant.store', 'Context'),
            'handler_class_name' => Yii::t('dotplant.store', 'Handler class name'),
            'packed_json_handler_params' => Yii::t('dotplant.store', 'Handler params'),
            'sort_order' => Yii::t('dotplant.store', 'Sort order'),
            'is_active' => Yii::t('dotplant.store', 'Is active'),
        ];
    }

    /**
     * Get list data for dropdown
     * @param $contextId int|null
     * @return string[]
     */
    public static function listData($contextId = null)
    {
        $condition = $contextId === null ? ['is_active' => 1] : ['context_id' => [0, $contextId], 'is_active' => 1];
        return (new MultilingualListDataQuery(static::class))
            ->where($condition)
            ->column();
    }
}
