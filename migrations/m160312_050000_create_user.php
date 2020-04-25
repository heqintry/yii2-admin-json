<?php

use yii\db\Migration;
use hqt\admin\components\Configs;

class m160312_050000_create_user extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $userTable = Configs::instance()->userTable;
        $db = Configs::userDb();

        // Check if the table exists
        if ($db->schema->getTableSchema($userTable, true) === null) {
            $this->createTable($userTable, [
                'id' => $this->primaryKey(),
                'username' => $this->string(32)->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string(),
                'email' => $this->string()->notNull(),
                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ], $tableOptions);

            $this->batchInsert($userTable, ['username', 'password_hash'], [
                ['admin', Yii::$app->security->generatePasswordHash('password')],
                ['user1', Yii::$app->security->generatePasswordHash('password')],
                ['user2', Yii::$app->security->generatePasswordHash('password')],
            ]);
        }
    }

    public function down()
    {
        $userTable = Configs::instance()->userTable;
        $db = Configs::userDb();
        if ($db->schema->getTableSchema($userTable, true) !== null) {
            $this->dropTable($userTable);
        }
    }
}
