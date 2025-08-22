<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PropertiesFixture
 */
class PropertiesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'address' => 'Lorem ipsum dolor sit amet',
                'city' => 'Lorem ipsum dolor sit amet',
                'state' => 'Lorem ipsum dolor sit amet',
                'zip' => 'Lorem ip',
                'beds' => 1,
                'baths' => 1,
                'sqft' => 1,
                'price' => 1,
                'photo' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-08-21 01:29:40',
            ],
        ];
        parent::init();
    }
}
