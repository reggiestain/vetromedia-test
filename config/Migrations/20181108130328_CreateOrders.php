<?php

use Migrations\AbstractMigration;

class CreateOrders extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $orders = $this->table('orders');
        $orders->addColumn('user_id', 'integer', ['limit' => 11])
            ->addColumn('exchange_rate', 'float', ['null' => false])
            ->addColumn('exchange_amount', 'float', ['null' => false])
            ->addColumn('foreign_currency_purchased', 'string', ['limit' => 3,'null' => false])
            ->addColumn('surcharge_percentage', 'float', ['null' => false])
            ->addColumn('amount_to_pay', 'float', ['null' => false])
            ->addColumn('amount_of_surcharge', 'float', ['null' => false])
            ->addColumn('amount_of_foreign_currency', 'float', ['null' => false])
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', ['null' => true])
            ->addIndex(['user_id'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
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
