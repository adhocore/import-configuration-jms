<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationFactoryTest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms;

use PHPUnit\Framework\TestCase;
use JMS\Serializer\SerializerBuilder;

/**
 * Test class for the JMS configuration factory.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationFactoryTest extends TestCase
{

    /**
     * The configuration factory instance.
     *
     * @var \TechDivision\Import\Configuration\Jms\ConfigurationFactory
     */
    protected $configurationFactory;

    /**
     *
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {

        // intialize the vendor directory
        $vendorDirectory = 'vendor';

        // the path of the JMS serializer directory, relative to the vendor directory
        $jmsDirectory = DIRECTORY_SEPARATOR . 'jms' . DIRECTORY_SEPARATOR . 'serializer' . DIRECTORY_SEPARATOR . 'src';

        // try to find the path to the JMS Serializer annotations
        if (!file_exists($annotationDirectory = $vendorDirectory . DIRECTORY_SEPARATOR . $jmsDirectory)) {
            // stop processing, if the JMS annotations can't be found
            throw new \Exception(
                sprintf(
                    'The jms/serializer libarary can not be found in %s',
                    $vendorDirectory
                )
            );
        }

        // register the autoloader for the JMS serializer annotations
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            $annotationDirectory
        );

        // create a mock configuration parser factory
        $mockConfigurationParserFactory = $this->getMockBuilder('TechDivision\Import\Configuration\Jms\ConfigurationParserFactory')
            ->disableOriginalConstructor()
            ->getMock();

        // create a new serializer builder instance
        $configurationBuilder = new SerializerBuilder();

        // initialize the configuration factory instance we want to test
        $this->configurationFactory = new ConfigurationFactory($mockConfigurationParserFactory, $configurationBuilder);
    }

    /**
     * Test's the factory() method.
     *
     * @return void
     */
    public function testFactory()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factory(
            __DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'
        );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\ConfigurationInterface', $configuration);
    }

    /**
     * Test's the factoryFromString() method with simple params.
     *
     * @return void
     */
    public function testFactoryFromStringWithSimpleParamsOption()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factoryFromString(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'),
            'json',
            '{"params":{"test":"test-01"}}'
        );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\ConfigurationInterface', $configuration);
        $this->assertSame('test-01', $configuration->getParam('test'));
    }

    /**
     * Test's the factoryFromString() method with complex params.
     *
     * @return void
     */
    public function testFactoryFromStringWithComplexParamsOption()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factoryFromString(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'),
            'json',
            '{"params":{"test":["test-01","test-02"]}}'
        );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\ConfigurationInterface', $configuration);
        $this->assertSame(array('test-01', 'test-02'), $configuration->getParam('test'));
    }

    /**
     * Test's the factoryFromString() method with merged complex params.
     *
     * @return void
     */
    public function testFactoryFromStringWithMergedComplexParamsOption()
    {

        // load the configuration
        $configuration = $this->configurationFactory->factoryFromString(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'techdivision-import.json'),
            'json',
            '{"params":{"test-array":["test-01","test-02"]}}'
            );

        // query whether or not the configuration instance has the expected type
        $this->assertInstanceOf('TechDivision\Import\ConfigurationInterface', $configuration);
        $this->assertSame(array('test-01', 'test-02'), $configuration->getParam('test-array'));
    }
}
