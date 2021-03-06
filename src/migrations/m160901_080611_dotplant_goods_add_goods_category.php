<?php

use app\helpers\PermissionsHelper;
use DevGroup\DataStructure\helpers\PropertiesTableGenerator;
use DotPlant\EntityStructure\models\Entity;
use DotPlant\Store\models\goods\GoodsCategory;
use yii\helpers\Console;
use yii\db\Migration;

class m160901_080611_dotplant_goods_add_goods_category extends Migration
{
    private static $permissionsConfig = [
        'StoreBackendProductManager' => [
            'descr' => 'Backend Store Product Manager Role',
            'permits' => [
                'store-backend-product-view' => 'Backend Product View',
                'store-backend-product-edit' => 'Backend Product Edit',
                'store-backend-product-activate' => 'Backend Product Activate',
            ]
        ],
        'StoreBackendProductAdministrator' => [
            'descr' => 'Backend Store Product Administrator Role',
            'permits' => [
                'store-backend-product-delete' => 'Backend Product Delete',
            ],
            'roles' => [
                'StoreBackendProductManager'
            ],
        ],
    ];

    public function up()
    {
        if (null === $this->db->getTableSchema(Entity::tableName())) {
            Console::stderr("Please, first install if not and activate 'DotPlant Entity Structure' extension!" . PHP_EOL);
            return false;
        }
        $this->insert(
            Entity::tableName(),
            [
                'name' => 'GoodsCategory',
                'class_name' => GoodsCategory::class
            ]
        );
        PropertiesTableGenerator::getInstance()->generate(GoodsCategory::class);
        PermissionsHelper::createPermissions(self::$permissionsConfig);
    }

    public function down()
    {
        $this->delete(
            Entity::tableName(),
            [
                'name' => 'Tickets',
                'class_name' => GoodsCategory::class
            ]
        );
        $this->delete(
            Entity::tableName(),
            [
                'name' => 'GoodsCategory',
                'class_name' => GoodsCategory::class
            ]
        );
        PropertiesTableGenerator::getInstance()->drop(GoodsCategory::class);
        PermissionsHelper::removePermissions(self::$permissionsConfig);
    }
}
