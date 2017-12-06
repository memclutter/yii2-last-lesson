<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lesson`.
 * Has foreign keys to the tables:
 *
 * - `group`
 */
class m171206_184317_create_lesson_table extends Migration
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

        $this->createTable('lesson', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'lesson_time' => $this->dateTime()->defaultValue(null),
            'group_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-lesson-name',
            'lesson',
            'name'
        );

        // creates index for column `group_id`
        $this->createIndex(
            'idx-lesson-group_id',
            'lesson',
            'group_id'
        );

        // add foreign key for table `group`
        $this->addForeignKey(
            'fk-lesson-group_id',
            'lesson',
            'group_id',
            'group',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `group`
        $this->dropForeignKey(
            'fk-lesson-group_id',
            'lesson'
        );

        // drops index for column `group_id`
        $this->dropIndex(
            'idx-lesson-group_id',
            'lesson'
        );

        $this->dropIndex(
            'idx-lesson-name',
            'lesson'
        );

        $this->dropTable('lesson');
    }
}
