<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter
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

namespace TechDivision\Import\Configuration\Jms\Configuration\Subject;

use JMS\Serializer\Annotation\Type;
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Configuration\Subject\FilesystemAdapterConfigurationInterface;

/**
 * The filesystem adapter's configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class FilesystemAdapter implements FilesystemAdapterConfigurationInterface
{

    /**
     * The filesystem adapter's class name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_ADAPTER_FILESYSTEM_FACTORY_PHP;

    /**
     * The filesystem specific adapter configuration.
     *
     * @var \TechDivision\Import\Configuration\Subject\FilesystemAdapter\AdapterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\FilesystemAdapter\Adapter")
     */
    protected $adapter;

    /**
     * Return's the filesystem adapter's unique DI identifier.
     *
     * @return string The filesystem adapter's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the filesystem specific adapter configuration.
     *
     * @return \TechDivision\Import\Configuration\Subject\FilesystemAdapter\AdapterConfigurationInterface The filesystem specific adapter configuration
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
