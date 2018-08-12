<?php
/**
 * Contains \app\modules\events\migrations\m180812_003457_create_event migration class
 */

namespace app\modules\events\migrations;

use deele\devkit\db\SchemaHelper;
use yii\db\Migration;

/**
 * Class m180812_003457_create_event creates "event" table
 *
 * @package modules\users
 */
class m180812_003457_create_event extends Migration
{

    public $tableName = 'event';
    public $translationsTableName = 'event__translation';

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
                                           ->comment('Event ID'),
                'event_type_id'    => $this->integer()
                                           ->notNull()
                                           ->comment('Type'),
                'status'           => $this->smallInteger()
                                           ->notNull()
                                           ->defaultValue(1)
                                           ->comment('Status'),
                'location_degrees' => $this->string()
                                           ->comment('Location - Decimal degrees'),
                'begin_at'         => $this->dateTime()
                                           ->comment('Begin at'),
                'end_at'           => $this->dateTime()
                                           ->comment('End at'),
                'created_at'       => $this->dateTime()
                                           ->comment('Created at'),
                'updated_at'       => $this->dateTime()
                                           ->comment('Updated at'),
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

            // Indexing and keys
            if ($createTable) {
                try {
                    $this->createIndex(
                        SchemaHelper::createIndexName([
                            'status',
                            'begin_at',
                            'end_at',
                        ]),
                        $tableName,
                        [
                            'status',
                            'begin_at',
                            'end_at',
                        ]
                    );

                    $typeTableName = SchemaHelper::prefixedTable('event__type');
                    $this->createIndex(
                        SchemaHelper::createIndexName('event_type_id'),
                        $tableName,
                        ['event_type_id']
                    );
                    $this->addForeignKey(
                        SchemaHelper::createForeignKeyName($this->tableName, 'event_type_id'),
                        $tableName,
                        'event_type_id',
                        $typeTableName,
                        'id',
                        'CASCADE',
                        'CASCADE'
                    );

                    $createTable = true;
                } catch (\Exception $e) {
                    $this->outputError('DB Exception ' . $e->getMessage());
                    $createTable = false;
                }
            }

            if ($createTable) {
                $createTable = $this->safeUpTranslations();
            }

            return $createTable;
        } else {
            $this->outputError('Table ' . $tableName . ' already exists!');
        }

        return false;
    }

    /**
     * Creates table
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function safeUpTranslations()
    {
        $tableName = SchemaHelper::prefixedTable($this->translationsTableName);
        if (!SchemaHelper::tablesExist($tableName)) {

            // Table options
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';

            // Create table
            $columns = [
                'event_id'    => $this->integer()
                                      ->notNull()
                                      ->comment('Event ID'),
                'language'    => $this->string(5)
                                      ->comment('Language'),
                'about'       => $this->string()
                                      ->comment('About'),
                'description' => $this->text()
                                      ->comment('Description'),
                'location'    => $this->string()
                                      ->comment('Location'),
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

            // Indexing and keys
            if ($createTable) {
                try {
                    $this->addPrimaryKey(
                        'primaryKey',
                        $tableName,
                        [
                            'event_id',
                            'language',
                        ]
                    );

                    $this->createIndex(
                        SchemaHelper::createIndexName('event_id'),
                        $tableName,
                        ['event_id']
                    );
                    $this->addForeignKey(
                        SchemaHelper::createForeignKeyName(
                            $this->translationsTableName,
                            'event_id'
                        ),
                        $tableName,
                        'event_id',
                        SchemaHelper::prefixedTable($this->tableName),
                        'id',
                        'CASCADE',
                        'CASCADE'
                    );

                    $createTable = true;
                } catch (\Exception $e) {
                    $this->outputError('DB Exception ' . $e->getMessage());
                    $createTable = false;
                }
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
        $dropTable = $this->safeDownTranslations();
        if ($dropTable) {
            $tableName = SchemaHelper::prefixedTable($this->tableName);
            try {

                // Indexing and keys
                $this->dropIndex(
                    SchemaHelper::createIndexName([
                        'status',
                        'begin_at',
                        'end_at',
                    ]),
                    $tableName
                );

                // Drop table
                $this->dropTable($tableName);

                $dropTable = true;
            } catch (\Exception $e) {
                $this->outputError('DB Exception ' . $e->getMessage());
                $dropTable = false;
            }
        }

        return $dropTable;
    }

    /**
     * Drops table
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function safeDownTranslations()
    {
        $tableName = SchemaHelper::prefixedTable($this->translationsTableName);
        try {

            // Indexing and keys
            $this->dropForeignKey(
                SchemaHelper::createForeignKeyName($this->translationsTableName, 'event_id'),
                $tableName
            );
            $this->dropIndex(
                SchemaHelper::createIndexName(['event_id']),
                $tableName
            );

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
