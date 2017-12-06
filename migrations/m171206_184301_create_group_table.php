<?php

use yii\db\Migration;

/**
 * Handles the creation of table `group`.
 * Has foreign keys to the tables:
 *
 * - `teacher`
 */
class m171206_184301_create_group_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('group', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'teacher_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `teacher_id`
        $this->createIndex(
            'idx-group-teacher_id',
            'group',
            'teacher_id'
        );

        // add foreign key for table `teacher`
        $this->addForeignKey(
            'fk-group-teacher_id',
            'group',
            'teacher_id',
            'teacher',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `teacher`
        $this->dropForeignKey(
            'fk-group-teacher_id',
            'group'
        );

        // drops index for column `teacher_id`
        $this->dropIndex(
            'idx-group-teacher_id',
            'group'
        );

        $this->dropTable('group');
    }
}
