<?php

use app\models\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Панель администратора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'status.name',
            [
                'label' => 'Изменить статус',
                'format' => 'html',
                'value' => function ($data) {
                    if ($data->status->code === 'new')
                    {
                        return Html::a('Подтвердить', ['admin/success', 'id' => $data->id])." / ".Html::a('Отклонить', ['admin/reject', 'id' => $data->id]);
                    }
                    return "Изменено";
                }
            ],
            [
                'label' => 'ФИО заявителя',
                'value' => function ($data) {
                    return $data->user->getFullName();
                }
            ],
            'auto_number',
            'text:ntext',
            'date',
        ],
    ]); ?>


</div>

<style scoped>

    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.25rem;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .pagination li {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #0B7bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

</style>
