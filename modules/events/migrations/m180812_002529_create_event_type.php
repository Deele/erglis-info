<?php
/**
 * Contains \app\modules\events\migrations\m180812_002529_create_event_type migration class
 */

namespace app\modules\events\migrations;

use deele\devkit\db\SchemaHelper;
use yii\db\Migration;

/**
 * Class m180812_002529_create_event_type creates "event" table
 *
 * @package modules\users
 */
class m180812_002529_create_event_type extends Migration
{

    public $tableName = 'event__type';
    public $translationsTableName = 'event__type_translation';

    /**
     * Outputs an error message
     *
     * @param string $message
     */
    public static function outputError($message)
    {
        echo "\n\nERROR! $message\n\n";
    }

    /**
     * Creates table
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function safeUp()
    {
        $tableName = SchemaHelper::prefixedTable($this->tableName);
        if (!SchemaHelper::tablesExist($tableName)) {

            // Table options
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';

            // Create table
            $columns = [
                'id'               => $this->primaryKey()
                                           ->comment('Event type ID'),
            ];
            try {
                parent::createTable(
                    $tableName,
                    $columns,
                    $tableOptions
                );
                $createTable = true;
            } catch (\Exception $e) {
                $this->outputError('DB Exception ' . $e->getMessage());
                $createTable = false;
            }

            return $createTable;
        } else {
            $this->outputError('Table ' . $tableName . ' already exists!');
        }

        return false;
    }

    /**
     * Drops table
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function safeDown()
    {
        $tableName = SchemaHelper::prefixedTable($this->tableName);
        try {

            // Drop table
            $this->dropTable($tableName);

            $dropTable = true;
        } catch (\Exception $e) {
            $this->outputError('DB Exception ' . $e->getMessage());
            $dropTable = false;
        }

        return $dropTable;
    }
}
