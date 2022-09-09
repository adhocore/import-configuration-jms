<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Cache
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

namespace TechDivision\Import\Configuration\Jms\Configuration;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use TechDivision\Import\Utils\CacheTypes;
use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Configuration\CacheConfigurationInterface;

/**
 * The cache configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Cache implements CacheConfigurationInterface
{

    /**
     * The cache type.
     *
     * @var string
     * @Type("string")
     * @SerializedName("type")
     */
    protected $type;

    /**
     * The cache's TTL (null === cache item NEVER times out).
     *
     * @var integer
     * @Type("integer")
     */
    protected $time = null;

    /**
     * The event name the listener has to be registered.
     *
     * @var string
     * @Type("boolean")
     */
    protected $enabled = true;

    /**
     * A reference to the parent configuration instance.
     *
     * @var \TechDivision\Import\ConfigurationInterface
     */
    protected $configuration;

    /**
     * Return's the cache type
     *
     * @return string The cache type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return's the flag whether the cache is enabled or not.
     *
     * The cache type cache.static is ALWAYS enabled, which is necessary e. g.
     * for the registration processor that contains the global data.
     *
     * @return boolean TRUE if the cache is enabled, else FALSE
     */
    public function isEnabled()
    {
        return (($this->enabled && $this->configuration->isCacheEnabled()) || $this->type === CacheTypes::TYPE_STATIC);
    }

    /**
     * Return's the cache TTL in seconds.
     *
     * @return integer The TTL
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set's the reference to the configuration instance.
     *
     * @param \TechDivision\Import\ConfigurationInterface $configuration The configuration instance
     *
     * @return void
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Return's the reference to the configuration instance.
     *
     * @return \TechDivision\Import\ConfigurationInterface The configuration instance
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
