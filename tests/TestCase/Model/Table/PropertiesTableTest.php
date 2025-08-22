<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PropertiesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PropertiesTable Test Case
 */
class PropertiesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PropertiesTable
     */
    protected $Properties;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Properties',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Properties') ? [] : ['className' => PropertiesTable::class];
        $this->Properties = $this->getTableLocator()->get('Properties', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Properties);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\PropertiesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
