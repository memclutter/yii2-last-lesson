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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('group', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'teacher_id' => $this->integer()->notNull(),
        ], $tableOptions);

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
