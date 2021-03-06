<?php


namespace DotPlant\Store\components;


use DevGroup\Frontend\Universal\ActionData;
use DotPlant\Monster\DataEntity\DataEntityProvider;
use DotPlant\Store\models\order\Order;
use DotPlant\Store\models\order\OrderDeliveryInformation;
use Yii;

class OrderDeliveryInformationByHashProvider extends DataEntityProvider
{
    /**
     * @var string the region key
     */
    public $regionKey = 'orderDeliveryInformationRegion';

    /**
     * @var string the material key
     */
    public $materialKey = 'orderDeliveryInformationMaterial';

    public function pack()
    {
        return [
            'class' => static::class,
            'entities' => $this->entities,
        ];
    }

    /**
     * @param ActionData $actionData
     *
     * @return mixed
     */
    public function getEntities(&$actionData)
    {
        $hash = Yii::$app->request->get('hash');
        $orderDeliveryInformation = OrderDeliveryInformation::find()->leftJoin(
            Order::tableName(),
            'order_id=' . Order::tableName() . '.id'
        )->where(['hash' => $hash])->one();
        return [
            $this->regionKey => [
                $this->materialKey => [
                    'orderDeliveryInformation' => $orderDeliveryInformation,
                ],
            ],
        ];
    }
}