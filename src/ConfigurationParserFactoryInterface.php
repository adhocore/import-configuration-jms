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

/**
 * The interface for all configuration parser factory implementations.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
interface ConfigurationParserFactoryInterface
{

    /**
     * Factory implementation to create a new configuration parser instance for the passed file format.
     *
     * @param string $format The format of the configuration file to create a configuration parser for (either one of json, yaml or xml)
     *
     * @return \TechDivision\Import\Configuration\Jms\ConfigurationParserInterface The configuration parser instance
     * @throws \Exception Is thrown if NO configuration parser mapping for the passed format has been specified
     */
    public function factory($format = 'json');
}
