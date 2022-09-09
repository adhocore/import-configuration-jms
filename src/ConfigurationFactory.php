<?php

/**
 * TechDivision\Import\Configuration\Jms\ConfigurationFactory
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

use JMS\Serializer\SerializerBuilder;
use TechDivision\Import\ConfigurationFactoryInterface;
use TechDivision\Import\Configuration\Jms\Configuration\Params;

/**
 * The configuration factory implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConfigurationFactory implements ConfigurationFactoryInterface
{

    /**
     * The configuration parser factory instance used to create a parser that
     * parsers and merges the configuration from different directories.
     *
     * @var \TechDivision\Import\Configuration\Jms\ConfigurationParserFactoryInterface
     */
    protected $configurationParserFactory;

    /**
     * The configuration class name to use.
     *
     * @var string
     */
    protected $configurationClassName;

    /**
     * The serializer builder instance.
     *
     * @var \JMS\Serializer\SerializerBuilder
     */
    protected $serializerBuilder;

    /**
     * Initializes the instance with the configuration parser factory instance.
     *
     * @param \TechDivision\Import\Configuration\Jms\ConfigurationParserFactoryInterface $configurationParserFactory The configuration parser factory instance
     * @param \JMS\Serializer\SerializerBuilder                                          $serializerBuilder          The serializer builder instance to use
     * @param string                                                                     $configurationClassName     The configuration class name to use
     */
    public function __construct(
        ConfigurationParserFactoryInterface $configurationParserFactory,
        SerializerBuilder $serializerBuilder,
        $configurationClassName = Configuration::class
    ) {
        $this->serializerBuilder = $serializerBuilder;
        $this->configurationClassName = $configurationClassName;
        $this->configurationParserFactory = $configurationParserFactory;
    }

    /**
     * Return's the configuration parser factory instance.
     *
     * @return \TechDivision\Import\Configuration\Jms\ConfigurationParserFactoryInterface The configuration parser factory instance
     */
    protected function getConfigurationParserFactory()
    {
        return $this->configurationParserFactory;
    }

    /**
     * Return's the configuration class name to use.
     *
     * @return string The configuration class name
     */
    protected function getConfigurationClassName()
    {
        return $this->configurationClassName;
    }

    /**
     * Return's the serializer builder instance to use.
     *
     * @return \JMS\Serializer\SerializerBuilder The serializer builder instance
     */
    protected function getSerializerBuilder()
    {
        return $this->serializerBuilder;
    }

    /**
     * Returns the configuration parser for the passed format.
     *
     * @param string $format The format of the configuration file to return the parser for (either one of json, yaml or xml)
     *
     * @return \TechDivision\Import\Configuration\Jms\ConfigurationParserInterface The configuration parser instance
     */
    protected function getConfigurationParser($format)
    {
        return $this->getConfigurationParserFactory()->factory($format);
    }

    /**
     * Factory implementation to create a new initialized configuration instance.
     *
     * @param string $filename   The configuration filename
     * @param string $format     The format of the configuration file, either one of json, yaml or xml
     * @param string $params     A serialized string with additional params that'll be passed to the configuration
     * @param string $paramsFile A filename that contains serialized data with additional params that'll be passed to the configuration
     *
     * @return \TechDivision\Import\Configuration\Jms\Configuration The configuration instance
     * @throws \Exception Is thrown, if the specified configuration file doesn't exist
     */
    public function factory($filename, $format = 'json', $params = null, $paramsFile = null)
    {

        // try to load the JSON data
        if ($data = file_get_contents($filename)) {
            // initialize the JMS serializer, load and return the configuration
            return $this->factoryFromString($data, $format, $params, $paramsFile);
        }

        // throw an exception if the data can not be loaded from the passed file
        throw new \Exception(sprintf('Can\'t load configuration file %s', $filename));
    }

    /**
     * Factory implementation to create a new initialized configuration instance from a file
     * with configurations that'll be parsed and merged.
     *
     * @param array  $directories An array with diretories to parse and merge
     * @param string $format      The format of the configuration file, either one of json, yaml or xml
     * @param string $params      A serialized string with additional params that'll be passed to the configuration
     * @param string $paramsFile  A filename that contains serialized data with additional params that'll be passed to the configuration
     *
     * @return void
     */
    public function factoryFromDirectories(array $directories = array(), $format = 'json', $params = null, $paramsFile = null)
    {
        return $this->factoryFromString($this->getConfigurationParser($format)->parse($directories), $format, $params, $paramsFile);
    }

    /**
     * Factory implementation to create a new initialized configuration instance.
     *
     * @param string $data       The configuration data
     * @param string $format     The format of the configuration data, either one of json, yaml or xml
     * @param string $params     A serialized string with additional params that'll be passed to the configuration
     * @param string $paramsFile A filename that contains serialized data with additional params that'll be passed to the configuration
     *
     * @return \TechDivision\Import\ConfigurationInterface The configuration instance
     */
    public function factoryFromString($data, $format = 'json', $params = null, $paramsFile = null)
    {

        // initialize the JMS serializer, load and return the configuration
        $data = $this->toArray($data, $this->getConfigurationClassName(), $format);

        // merge the params, if specified with the --params option
        if ($params) {
            $this->replaceParams(
                $data,
                $this->toArray(
                    $params,
                    Params::class,
                    $format
                )
            );
        }

        // merge the param loaded from the file, if specified with the --params-file option
        if ($paramsFile && is_file($paramsFile)) {
            $this->replaceParams(
                $data,
                $this->toArray(
                    file_get_contents($paramsFile),
                    Params::class,
                    pathinfo($paramsFile, PATHINFO_EXTENSION)
                )
            );
        }

        // finally, create and return the configuration from the merge data
        return $this->getSerializerBuilder()->build()->fromArray($data, $this->getConfigurationClassName());
    }

    /**
     * Creates and returns an array/object tree from the passed array.
     *
     * @param array  $data The data to create the object tree from
     * @param string $type The object type to create
     *
     * @return mixed The array/object tree from the passed array
     */
    protected function fromArray(array $data, $type)
    {
        return $this->getSerializerBuilder()->build()->fromArray($data, $type);
    }

    /**
     * Deserializes the data, converts it into an array and returns it.
     *
     * @param string $data   The data to convert
     * @param string $type   The object type for the deserialization
     * @param string $format The data format, either one of JSON, XML or YAML
     *
     * @return array The data as array
     */
    protected function toArray($data, $type, $format)
    {

        // load the serializer builde
        $serializer = $this->getSerializerBuilder()->build();

        // deserialize the data, convert it into an array and return it
        return $serializer->toArray($serializer->deserialize($data, $type, $format));
    }

    /**
     * Merge the additional params in the passed configuration data and replaces existing ones with the same name.
     *
     * @param array $data   The array with configuration data
     * @param array $params The array with additional params to merge
     *
     * @return void
     */
    protected function replaceParams(&$data, $params)
    {
        $data = array_replace_recursive($data, $params);
    }
}
