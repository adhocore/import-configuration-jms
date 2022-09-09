<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Plugin
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
use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Configuration\PluginConfigurationInterface;
use TechDivision\Import\Configuration\OperationConfigurationInterface;
use TechDivision\Import\Configuration\ListenerAwareConfigurationInterface;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\ImportAdapter;
use TechDivision\Import\Configuration\Jms\Configuration\Subject\ExportAdapter;

/**
 * A simple plugin configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Plugin implements PluginConfigurationInterface, ListenerAwareConfigurationInterface
{

    /**
     * The trait that provides parameter configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ListenersTrait
     */
    use ListenersTrait;

    /**
     * The main configuration.
     *
     * @var string
     */
    protected $configuration;

    /**
     * The execution context.
     *
     * @var \TechDivision\Import\Configuration\OperationConfigurationInterface
     */
    protected $operationConfiguration;

    /**
     * The plugin's unique DI identifier.
     *
     * @var string
     * @Type("string")
     * @SerializedName("id")
     */
    protected $id;

    /**
     * The plugin's name.
     *
     * @var string
     * @Type("string")
     * @SerializedName("name")
     */
    protected $name;

    /**
     * ArrayCollection with the information of the configured subjects.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\Subject>")
     */
    protected $subjects;

    /**
     * The swift mailer configuration to use.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\SwiftMailer
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\SwiftMailer")
     * @SerializedName("swift-mailer")
     */
    protected $swiftMailer;

    /**
     * The import adapter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\ImportAdapterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\ImportAdapter")
     * @SerializedName("import-adapter")
     */
    protected $importAdapter;

    /**
     * The export adapter configuration instance.
     *
     * @var \TechDivision\Import\Configuration\Subject\ExportAdapterConfigurationInterface
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\Subject\ExportAdapter")
     * @SerializedName("export-adapter")
     */
    protected $exportAdapter;

    /**
     * Lifecycle callback that will be invoked after deserialization.
     *
     * @return void
     * @PostDeserialize
     */
    public function postDeserialize()
    {

        // create an empty collection if no subjects has been specified
        if ($this->subjects === null) {
            $this->subjects = new ArrayCollection();
        }

        // set a default import adatper if none has been configured
        if ($this->importAdapter === null) {
            $this->importAdapter = new ImportAdapter();
        }

        // set a default export adatper if none has been configured
        if ($this->exportAdapter === null) {
            $this->exportAdapter = new ExportAdapter();
        }
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

    /**
     * Return's the plugin's unique DI identifier.
     *
     * @return string The plugin's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the plugin's name or the ID, if the name is NOT set.
     *
     * @return string The plugin's name
     * @see \TechDivision\Import\Configuration\PluginConfigurationInterface::getId()
     */
    public function getName()
    {
        return $this->name ? $this->name : $this->getId();
    }

    /**
     * Return's the ArrayCollection with the operation's subjects.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The ArrayCollection with the operation's subjects
     */
    public function getSubjects()
    {

        // initialize the array with the subject configurations
        $subjects = array();

        // iterate over the subject configurations
        foreach ($this->subjects as $subject) {
            // inject the parent plugin configuration
            $subject->setPluginConfiguration($this);
            // add the subject to the array
            $subjects[] = $subject;
        }

        // return the array with subject configurations
        return $subjects;
    }

    /**
     * Return's the swift mailer configuration to use.
     *
     * @return \TechDivision\Import\Configuration\Jms\Configuration\SwiftMailer The swift mailer configuration to use
     */
    public function getSwiftMailer()
    {
        return $this->swiftMailer;
    }

    /**
     * Return's the import adapter configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\ImportAdapterConfigurationInterface The import adapter configuration instance
     */
    public function getImportAdapter()
    {
        return $this->importAdapter;
    }

    /**
     * Return's the export adapter configuration instance.
     *
     * @return \TechDivision\Import\Configuration\Subject\ExportAdapterConfigurationInterface The export adapter configuration instance
     */
    public function getExportAdapter()
    {
        return $this->exportAdapter;
    }

    /**
     * Set's the configuration of the operation the plugin has been configured for.
     *
     * @param \\TechDivision\Import\Configuration\OperationConfigurationInterface $operationConfiguration The operation configuration
     *
     * @return void
     */
    public function setOperationConfiguration(OperationConfigurationInterface $operationConfiguration)
    {
        $this->operationConfiguration = $operationConfiguration;
    }

    /**
     * Return's the configuration of the operation the plugin has been configured for.
     *
     * @return \TechDivision\Import\Configuration\OperationConfigurationInterface The operation configuration
     */
    public function getOperationConfiguration()
    {
        return $this->operationConfiguration;
    }

    /**
     * Return's the execution context configuration for the actualy plugin configuration.
     *
     * @return \TechDivision\Import\ExecutionContextInterface The execution context to use
     */
    public function getExecutionContext()
    {
        return $this->getOperationConfiguration()->getExecutionContext();
    }

    /**
     * Return's the full opration name, which consists of the Magento edition, the entity type code and the operation name.
     *
     * @param string $separator The separator used to seperate the elements
     *
     * @return string The full operation name
     */
    public function getFullOperationName($separator = '/')
    {
        return $this->getOperationConfiguration()->getFullName();
    }
}
