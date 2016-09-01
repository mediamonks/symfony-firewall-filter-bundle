<?php

namespace MediaMonks\FirewallFilterBundle\Tests\Functional;

use MediaMonks\FirewallFilterBundle\Tests\Functional\app\TestKernel;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AbstractFunctionalTestCase in package MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\Functional
 *
 * @author pawel@mediamonks.com
 */
abstract class AbstractFunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    protected $testCase;

    protected function setUp()
    {
        parent::setUp();

        $this->deleteTmpDir($this->testCase);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->deleteTmpDir($this->testCase);
    }

    protected static function deleteTmpDir($testCase)
    {
        if (!file_exists($dir = sys_get_temp_dir() . '/' . Kernel::VERSION . '/'.$testCase)) {
            return;
        }

        $fs = new Filesystem();
        $fs->remove($dir);
    }

    protected static function getKernelClass()
    {
        require_once __DIR__ . '/app/TestKernel.php';

        return 'MediaMonks\FirewallFilterBundle\Tests\Functional\app\TestKernel';
    }

    /**
     * @param array $options
     * @return TestKernel
     */
    protected function createKernel(array $options = array())
    {
        $class = self::getKernelClass();

        return new $class(
            $this->testCase,
            isset($options['root_config']) ? $options['root_config'] : 'config.yml',
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}