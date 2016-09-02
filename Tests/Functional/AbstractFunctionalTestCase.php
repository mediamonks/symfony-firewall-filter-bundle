<?php

namespace MediaMonks\FirewallFilterBundle\Tests\Functional;

use MediaMonks\FirewallFilterBundle\Tests\Functional\app\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Mockery as m;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AbstractFunctionalTestCase in package MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\Functional
 *
 * @author pawel@mediamonks.com
 */
abstract class AbstractFunctionalTestCase extends WebTestCase
{
    use m\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function tearDown()
    {
        $this->assertPostConditions();
        parent::tearDown();
    }

    protected static function getKernelClass()
    {
        require_once __DIR__ . '/app/TestKernel.php';

        return 'MediaMonks\FirewallFilterBundle\Tests\Functional\app\TestKernel';
    }

    /**
     * @param array $options
     * @return TestKernel
     * @throws \Exception
     */
    protected static function createKernel(array $options = array())
    {
        $class = static::getKernelClass();

        if(!isset($options['test_case'])){
            throw new \Exception('test_case is required');
        }

        return new $class(
            $options['test_case'],
            isset($options['root_config']) ? $options['root_config'] : 'config.yml',
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}