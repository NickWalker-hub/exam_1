<?php

use app\models\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать запрос', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'status.name',
            'auto_number',
            'text:ntext',
//             //'date',
//             [
//                 'class' => ActionColumn::className(),
//                 'urlCreator' => function ($action, Request $model, $key, $index, $column) {
//                     return Url::toRoute([$action, 'id' => $model->id]);
//                  }
//             ],
        ],
    ]); ?>


</div>
