<?php

/**
 * @var int $contextId
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\web\View $this
 */

use DevGroup\AdminUtils\columns\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('dotplant.store', 'Payments');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <div class="box-body">
        <?=
        \DotPlant\Store\widgets\backend\ContextTabs::widget(
            [
                'contextId' => $contextId,
            ]
        )
        ?>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'id' => 'payments-grid',
            'columns' => [
                [
                    'class' => \app\modules\admin\widgets\SortColumn::class,
                    'gridContainerId' => 'payments-grid',
                ],
                'id',
                'context_id',
                'handler_class_name',
                'is_active',
                ['class' => ActionColumn::class],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
    <?php if (Yii::$app->user->can('dotplant-store-payment-create')) : ?>
    <div class="box-footer">
        <div class="pull-right">
            <?=
            Html::a(Yii::t('dotplant.store', 'Create'),
                ['edit', 'contextId' => $contextId],
                ['class' => 'btn btn-success']
            )
            ?>
        </div>
    </div>
    <?php endif; ?>
</div>
