<?php

use Migrations\AbstractSeed;

/**
 * Currencies seed.
 */
class CurrenciesSeed extends AbstractSeed {

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run() {
        $data = [
            
            [
                'name' => 'US Dollars',
                'rate' => 0.0808279,
                'code' => 'USD',
                'surcharge' => 7.5,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'British Pound',
                'rate' => 0.0527032,
                'code' => 'GBP',
                'surcharge' => 5,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Euro',
                'rate' => 0.0718710,
                'code' => 'EUR',
                'surcharge' => 5,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kenyan Shilling',
                'code' => 'KES',
                'rate' => 7.81498,
                'surcharge' => 2.5,
                'created' => date('Y-m-d H:i:s'),
            ]
        ];

        $currencies = $this->table('currencies');
        $currencies->insert($data)
            ->save();
    }

}
