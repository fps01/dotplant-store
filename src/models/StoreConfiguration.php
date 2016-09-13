<?php

namespace DotPlant\Store\models;

use DevGroup\ExtensionsManager\models\BaseConfigurationModel;
use DotPlant\Store\models\order\OrderStatus;
use DotPlant\Store\models\order\OrderStatusTranslation;
use DotPlant\Store\Module;

class StoreConfiguration extends BaseConfigurationModel
{
    /**
     * @inheritdoc
     */
    public function getModuleClassName()
    {
        return Module::class;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['newOrderStatusId'],
                'each',
                'rule' => [
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => OrderStatusTranslation::class,
                    'targetAttribute' => ['newOrderStatusId' => 'model_id'],
                ],
            ],
            [
                ['paidOrderStatusId'],
                'each',
                'rule' => [
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => OrderStatusTranslation::class,
                    'targetAttribute' => ['paidOrderStatusId' => 'model_id'],
                ],
            ],
            [
                ['doneOrderStatusId'],
                'each',
                'rule' => [
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => OrderStatusTranslation::class,
                    'targetAttribute' => ['doneOrderStatusId' => 'model_id'],
                ],
            ],
            [
                ['canceledOrderStatusId'],
                'each',
                'rule' => [
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => OrderStatusTranslation::class,
                    'targetAttribute' => ['canceledOrderStatusId' => 'model_id'],
                ],
            ],
            [
                ['allowToAddSameGoods', 'countUniqueItemsOnly', 'singlePriceForWarehouses', 'registerGuestInCart'],
                'boolean',
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newOrderStatusId' => \Yii::t('dotplant.store', 'New'),
            'paidOrderStatusId' => \Yii::t('dotplant.store', 'Paid'),
            'doneOrderStatusId' => \Yii::t('dotplant.store', 'Done'),
            'canceledOrderStatusId' => \Yii::t('dotplant.store', 'Canceled'),
            'allowToAddSameGoods' => \Yii::t('dotplant.store', 'Allow to add same goods to cart'),
            'countUniqueItemsOnly' => \Yii::t('dotplant.store', 'Count unique cart items only'),
            'singlePriceForWarehouses' => \Yii::t('dotplant.store', 'Use a single price for warehouses'),
            'registerGuestInCart' => \Yii::t('dotplant.store', 'Register guest in cart'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function webApplicationAttributes()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function consoleApplicationAttributes()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function commonApplicationAttributes()
    {
        return [
            'components' => [
                'i18n' => [
                    'translations' => [
                        'dotplant.store' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'messages',
                        ],
                    ],
                ],
            ],
            'modules' => [
                'store' => [
                    'class' => Module::class,
                    'layout' => '@app/views/layouts/admin',
                    'allowToAddSameGoods' => $this->allowToAddSameGoods,
                    'countUniqueItemsOnly' => $this->countUniqueItemsOnly,
                    'singlePriceForWarehouses' => $this->singlePriceForWarehouses,
                    'registerGuestInCart' => $this->registerGuestInCart,
                    'newOrderStatusId' => $this->newOrderStatusId,
                    'paidOrderStatusId' => $this->paidOrderStatusId,
                    'doneOrderStatusId' => $this->doneOrderStatusId,
                    'canceledOrderStatusId' => $this->canceledOrderStatusId,
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function appParams()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function aliases()
    {
        return [
            '@DotPlant/Store' => realpath(dirname(__DIR__)),
        ];
    }
}
