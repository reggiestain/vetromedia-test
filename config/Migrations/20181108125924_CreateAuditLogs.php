<?php
use Migrations\AbstractMigration;

class CreateAuditLogs extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $auditlogs = $this->table('audit_logs');
        $auditlogs->addColumn('user_id', 'integer', ['limit' => 11])
              ->addColumn('event', 'string', ['limit' => 50])                     
              ->addColumn('created', 'datetime')
              ->addColumn('updated', 'datetime', ['null' => true])
              ->addIndex(['user_id'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();
    }    
    /**
     * Migrate Up.
     */
    public function up()
    {

    }
    /**
     * Migrate Down.
     */
    public function down()
    {
     $this->dropTable('currencies');
    }
}
