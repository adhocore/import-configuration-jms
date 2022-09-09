<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationParserFactory
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
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Configuration\Jms;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The configuration parser factory implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationParserFactory implements ConfigurationParserFactoryInterface
{

    /**
     * The container instance.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * The format => parser mappings.
     *
     * @var array
     */
    protected $parserMappings;

    /**
     * Initializes the configuration parser factory.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container      The container instance
     * @param array                                                     $parserMappings The parser mappings
     */
    public function __construct(ContainerInterface $container, array $parserMappings)
    {
        $this->container = $container;
        $this->parserMappings = $parserMappings;
    }

    /**
     * Factory implementation to create a new configuration parser instance for the passed file format.
     *
     * @param string $format The format of the configuration file to create a configuration parser for (either one of json, yaml or xml)
     *
     * @return \TechDivision\Import\Configuration\Jms\ConfigurationParserInterface The configuration parser instance
     * @throws \Exception Is thrown if NO configuration parser mapping for the passed format has been specified
     */
    public function factory($format = 'json')
    {

        // query whether or not a configuration parser mapping is available
        if (isset($this->parserMappings[$format])) {
            return $this->container->get($this->parserMappings[$format]);
        }

        // throw an exception, if NO mapping for the passed format has been available
        throw new \Exception(sprintf('Can\t find a configuration parser mapping for format "%s"', $format));
    }
}
