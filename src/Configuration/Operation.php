<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Operation
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
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\PostDeserialize;
use Doctrine\Common\Collections\ArrayCollection;
use TechDivision\Import\ExecutionContextInterface;
use TechDivision\Import\Configuration\OperationConfigurationInterface;
use TechDivision\Import\Configuration\ListenerAwareConfigurationInterface;

/**
 * The configuration implementation for the options.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Operation implements OperationConfigurationInterface, ListenerAwareConfigurationInterface
{

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ListenersTrait
     */
    use ListenersTrait;

    /**
     * The execution context.
     *
     * @var \TechDivision\Import\ExecutionContextInterface
     */
    protected $executionContext;

    /**
     * The operation's name.
     *
     * @var string
     * @Exclude()
     */
    protected $name;

    /**
     * ArrayCollection with the information of the configured plugins.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\Plugin>")
     */
    protected $plugins;

    /**
     * Initialize the operation with the passed name.
     *
     * @param string|null $name The operation name
     */
    public function __construct($name = null)
    {
        if ($name != null) {
            $this->name = $name;
        }
    }

    /**
     * Lifecycle callback that will be invoked after deserialization.
     *
     * @return void
     * @PostDeserialize
     */
    public function postDeserialize()
    {

        // create an empty collection if no plugins has been specified
        if ($this->plugins === null) {
            $this->plugins= new ArrayCollection();
        }
    }

    /**
     * Query's whether or not the passed operation equals this instance.
     *
     * @param \TechDivision\Import\Configuration\OperationConfigurationInterface $operation The operation to query
     *
     * @return boolean TRUE if the operations are equal, else FALSE
     */
    public function equals(OperationConfigurationInterface $operation)
    {
        return strcasecmp($this->getName(), $operation->getName()) === 0;
    }

    /**
     * Set's the operation's name.
     *
     * @param string $name The operation's name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return's the operation's name.
     *
     * @return string The operation's name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return's the ArrayCollection with the operation's plugins.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The ArrayCollection with the operation's plugins
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * Set's the execution context configuration for the actualy plugin configuration.
     *
     * @param \TechDivision\Import\ExecutionContextInterface $executionContext The execution context to use
     *
     * @return void
     */
    public function setExecutionContext(ExecutionContextInterface $executionContext)
    {
        $this->executionContext = $executionContext;
    }

    /**
     * Return's the execution context configuration for the actualy plugin configuration.
     *
     * @return \TechDivision\Import\ExecutionContextInterface The execution context to use
     */
    public function getExecutionContext()
    {
        return $this->executionContext;
    }

    /**
     * String representation of the operation (the name).
     *
     * @return string The operation name
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Return's the full opration name, which consists of the Magento edition, the entity type code and the operation name.
     *
     * @param string $separator The separator used to seperate the elements
     *
     * @return string The full operation name
     */
    public function getFullName($separator = '/')
    {
        return implode(
            $separator,
            array(
                $this->getExecutionContext()->getMagentoEdition(),
                $this->getExecutionContext()->getEntityTypeCode(),
                $this->getName()
            )
        );
    }
}
