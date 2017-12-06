<?php

namespace app\commands;

use app\models\Group;
use app\models\Lesson;
use app\models\Teacher;
use Faker\Factory;
use Faker\Generator;
use SebastianBergmann\CodeCoverage\Report\Xml\Facade;
use Yii;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\db\Expression;

class DemoController extends Controller
{
    /**
     * @var Generator
     */
    private $faker;

    /**
     * Generate demo data for Teacher model.
     *
     * @param int $count default 100
     */
    public function actionTeacher($count = 100)
    {
        $faker = $this->getFaker();

        for ($i = 0; $i < $count; $i++) {
            $teacher = new Teacher();

            $teacher->first_name = $faker->firstName;
            $teacher->last_name  = $faker->lastName;

            if ($teacher->save()) {
                $this->stdout(Yii::t('app', 'Teacher model \'{teacherName}\' added', [
                        'teacherName' => (string)$teacher,
                    ]) . PHP_EOL);
            } else {
                $this->stderr(Yii::t('app', 'Could not create Teacher model \'{teacherName}\'', [
                    'teacherName' => (string)$teacher,
                ]) . PHP_EOL);
            }
        }
    }

    /**
     * Generate demo data for Group model.
     *
     * @param int $count default 50
     */
    public function actionGroup($count = 50)
    {
        for ($i = 0; $i < $count; $i++) {
            $group = new Group();

            $group->name       = $this->getRandomGroupName();
            $group->teacher_id = $this->findRandomTeacher()->id;

            if ($group->save()) {
                $this->stdout(Yii::t('app', 'Group model \'{groupName}\' added', [
                    'groupName' => (string)$group,
                ]) . PHP_EOL);
            } else {
                $this->stderr(Yii::t('app', 'Could not create Group model \'{groupName}\'', [
                    'groupName' => (string)$group,
                ]) . PHP_EOL);
            }
        }
    }

    public function actionLesson($count = 500)
    {
        for ($i = 0; $i < $count; $i++) {
            $lesson = new Lesson();

            $lesson->name = $this->getRandomLessonName();
            $lesson->group_id = $this->findRandomGroup()->id;
            $lesson->lesson_time = $this->getRandomLessonTime();

            if ($lesson->save()) {
                $this->stdout(Yii::t('app', 'Lesson model \'{lessonName}\' added', [
                    'lessonName' => (string)$lesson,
                ]) . PHP_EOL);
            } else {
                $this->stderr(Yii::t('app', 'Could not create Lesson model \'{lessonName}\'', [
                    'lessonName' => (string)$lesson,
                ]) . PHP_EOL);
            }
        }
    }

    /**
     * Faker getter method
     *
     * @return \Faker\Generator
     */
    private function getFaker()
    {
        if (!($this->faker instanceof Generator)) {
            $this->faker = Factory::create('ru_RU');
        }

        return $this->faker;
    }

    /**
     * Find one random teacher model
     *
     * @return Teacher|ActiveRecord
     */
    private function findRandomTeacher()
    {
        return Teacher::find()
            ->orderBy(new Expression('rand()'))
            ->limit(1)
            ->one();
    }

    /**
     * Find one random group model
     *
     * @return Group|ActiveRecord
     */
    private function findRandomGroup()
    {
        return Group::find()
            ->orderBy(new Expression('rand()'))
            ->limit(1)
            ->one();
    }

    /**
     * Get random group name
     *
     * @return string
     */
    private function getRandomGroupName()
    {
        $faker = $this->getFaker();

        return implode('', [
            strtoupper($faker->randomLetter),
            '-',
            $faker->randomDigitNotNull,
            $faker->randomDigitNotNull,
            $faker->randomDigitNotNull,
            $faker->randomDigitNotNull,
        ]);
    }

    /**
     * Get random lesson name
     *
     * @return string
     */
    private function getRandomLessonName()
    {
        return implode('', [
            'Урок ',
            rand(1, 1000),
        ]);
    }

    /**
     * Generate random lesson time
     *
     * @return string|null
     */
    private function getRandomLessonTime()
    {
        $faker = $this->getFaker();

        return $faker->boolean
            ? $this->getFaker()->dateTimeBetween('now', '+7 days')->format('Y-m-d H:i:s')
            : null;
    }
}