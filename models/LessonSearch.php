<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lesson;
use yii\db\Expression;

/**
 * LessonSearch represents the model behind the search form about `app\models\Lesson`.
 */
class LessonSearch extends Lesson
{
    /**
     * @var string search text
     */
    public $search;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Lesson::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'lesson_time' => SORT_ASC,
                ],
                'attributes' => [
                    'lesson_time' => [
                        'asc' => [new Expression('(case when lesson_time is not null then 0 else 1 end), lesson_time')],
                        'desc' => [new Expression('(case when lesson_time is not null then 0 else 1 end), lesson_time DESC')],
                        'default' => SORT_ASC,
                    ],
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // only current week lessons
        $query->andWhere(new Expression('yearweek(lesson_time, 1) = yearweek(curdate(), 1)'))
            ->andWhere(new Expression('lesson_time >= curdate()'));

        if (!empty($this->search)) {
            $query->joinWith('group')
                ->joinWith('group.teacher')
                ->andFilterWhere([
                    'or',
                    ['like', 'group.name', $this->search],
                    ['like', 'teacher.first_name', $this->search],
                    ['like', 'teacher.last_name', $this->search],
                ]);
        }

        return $dataProvider;
    }
}
