<?php

use yii\db\Migration;

/**
 * Handles the creation of table `teacher`.
 */
class m171206_184030_create_teacher_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('teacher', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
        ]);

        $this->createIndex(
            'idx-teacher-name',
            'teacher',
            ['first_name', 'last_name']
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-teacher-name',
            'teacher'
        );

        $this->dropTable('teacher');
    }
}
