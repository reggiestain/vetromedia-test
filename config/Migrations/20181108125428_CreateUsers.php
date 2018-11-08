<?php

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $users = $this->table('users');
        $users->addColumn('first_name', 'string', ['limit' => 30])
            ->addColumn('surname', 'string', ['limit' => 30])
            ->addColumn('username', 'string', ['limit' => 20])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('password', 'string', ['limit' => 100])
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', ['null' => true])
            ->addIndex(['username', 'email'], ['unique' => true])
            ->create();
    }
    /**
     * Migrate Up.
     */
    public function up() {
        
    }
    /**
     * Migrate Down.
     */
    public function down() {
        $this->dropTable('users');
    }

}
