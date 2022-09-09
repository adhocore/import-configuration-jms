<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Logger
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

namespace TechDivision\Import\Configuration\Jms\Configuration;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\PostDeserialize;
use Doctrine\Common\Collections\ArrayCollection;
use TechDivision\Import\Configuration\LoggerConfigurationInterface;

/**
 * The logger configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Logger implements LoggerConfigurationInterface
{

    /**
     * The trait that provides parameter handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * The logger's channel name to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("channel-name")
     */
    protected $channelName;

    /**
     * The logger's unique name to use.
     *
     * @var string
     * @Type("string")
     */
    protected $name;

    /**
     * The logger's type to use.
     *
     * @var string
     * @Type("string")
     */
    protected $type;

    /**
     * The DI name of the factory used to create the logger instance.
     *
     * @var string
     * @Type("string")
     */
    protected $id = 'import.logger.factory';

    /**
     * ArrayCollection with the information of the configured processors.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\Logger\Processor>")
     */
    protected $processors;

    /**
     * ArrayCollection with the information of the configured handlers.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\Logger\Handler>")
     */
    protected $handlers;

    /**
     * Lifecycle callback that will be invoked after deserialization.
     *
     * @return void
     * @PostDeserialize
     */
    public function postDeserialize()
    {

        // create an empty collection if no processors has been specified
        if ($this->processors === null) {
            $this->processors = new ArrayCollection();
        }

        // create an empty collection if no handlers has been specified
        if ($this->handlers === null) {
            $this->handlers = new ArrayCollection();
        }
    }

    /**
     * Return's the logger's channel name to use.
     *
     * @return string The channel name
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * Return's the logger's unique name to use.
     *
     * @return string The unique name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return's the DI name of the factory used to create the logger instance.
     *
     * @return string The DI name of the factory to use
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the logger's type to use.
     *
     * @return string The type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return's the array with the logger's processors.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The ArrayCollection with the processors
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * Return's the array with the logger's handlers.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The ArrayCollection with the handlers
     */
    public function getHandlers()
    {
        return $this->handlers;
    }
}
