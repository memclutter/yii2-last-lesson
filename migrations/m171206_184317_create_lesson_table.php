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
    public function up()
    {
        $this->createTable('lesson', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'lesson_time' => $this->dateTime()->defaultValue(null),
            'group_id' => $this->integer()->notNull(),
        ]);

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
    public function down()
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
