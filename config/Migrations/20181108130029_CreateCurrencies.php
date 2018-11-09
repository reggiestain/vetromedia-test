<?php

use Migrations\AbstractMigration;

class CreateCurrencies extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $currencies = $this->table('currencies');
        $currencies->addColumn('name', 'string', ['limit' => 30])
            ->addColumn('code', 'string', ['limit' => 3])
            ->addColumn('rate', 'float')
            ->addColumn('surcharge', 'float')           
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', ['null' => true])
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
        $this->dropTable('currencies');
    }

}
