<?php
/**
 * Events management controller index action view
 *
 * @see \app\controllers\mgmt\EventsMgmtController::actionIndex()
 *
 * @var yii\web\View $this
 * @var app\base\models\EventSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use kartik\grid\GridView;
use yii\helpers\Html;

/** @noinspection CssUnusedSymbol */
$this->registerCss(
    <<<CSS
.events-index .col_id {
    width: 7%;
}
.events-index .kv-grid-table {
    margin-top: 10px;
}
CSS
);
?><div class="events-index">
    <?= $this->render('_header') ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider'            => $dataProvider,
        'filterModel'             => $searchModel,
        'columns'                 => [
            [
                'attribute'      => 'id',
                'class'          => \kartik\grid\DataColumn::class,
                'label'          => Yii::t('app.modules.events.Event', 'ID'),
                'headerOptions'  => ['class' => 'col_id'],
                'contentOptions' => ['class' => 'col_id'],
            ],
            [
                'attribute'      => 'about',
                'class'          => \kartik\grid\DataColumn::class,
                'headerOptions'  => ['class' => 'col_about'],
                'contentOptions' => ['class' => 'col_about'],
            ],
            [
                'class'          => 'kartik\grid\ActionColumn',
                'headerOptions'  => ['class' => 'col_actions'],
                'contentOptions' => ['class' => 'col_actions'],
                'buttonOptions' => ['class' => 'btn btn-link'],
                'width' => '100px',
                'template' => Html::tag(
                    'div',
                    '{view} {update} {delete}',
                    [
                        'class' => 'btn-group btn-group-xs',
                        'role' => 'group',
                    ]
                )
            ],
        ],
        'resizableColumns'        => true,
        'resizableColumnsOptions' => [
            'resizeFromBody' => true
        ],
        'responsive'              => true,
        'hover'                   => true,
        'pjax'                    => true,
        'pjaxSettings'            => [
            'neverTimeout' => true,
        ],
        'layout'                  => "{toolbarContainer}\n{summary}\n{items}\n{pager}",
        'toolbarContainerOptions' => ['class' => 'btn-toolbar kv-grid-toolbar toolbar-container text-right'],
        'toolbar'                 => [
            [
                'content' =>
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i> ' .
                        Yii::t('app.common', 'Create new'),
                        [
                            'create'
                        ],
                        ['class' => 'btn btn-sm btn-success']
                    ) .
                    ' ' .
                    Html::a(
                        '<i class="glyphicon glyphicon-repeat"></i>',
                        ['index'],
                        [
                            'class' => 'btn btn-sm btn-default',
                            'title' => Yii::t('app.common', 'Reset')
                        ]
                    ) .
                    ' ' .
                    Html::a(
                        '<i class="glyphicon glyphicon-trash"></i>',
                        ['delete-all'],
                        [
                            'class' => 'btn btn-sm btn-danger',
                            'title' => Yii::t('app.common', 'Delete all'),
                            'data' => [
                                'confirm' => 'Are you sure you want to delete all entries?',
                                'method' => 'post',
                            ],
                        ]
                    ),
            ],
            '{toggleData}'
        ],
        'toggleDataContainer'     => ['class' => 'btn-group-sm'],
        'exportContainer'         => ['class' => 'btn-group-sm']
    ]); ?>
</div>
