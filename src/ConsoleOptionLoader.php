<?php

/**
 * TechDivision\Import\Configuration\Jms\ConsoleOptionLoader
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

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;
use TechDivision\Import\ConfigurationInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Console\Input\InputInterface;
use TechDivision\Import\ConsoleOptionLoaderInterface;
use TechDivision\Import\Utils\InputOptionKeysInterface;

/**
 * A dynamic console option loader implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ConsoleOptionLoader implements ConsoleOptionLoaderInterface
{

    /**
     * Array with options values that should NOT override the one from the configuration, if they already have values.
     *
     * @var array
     */
    protected $overrideIfEmpty = array();

    /**
     * The array with the blacklisted options that should be ignored.
     *
     * @var array
     */
    protected $blacklist = array();

    /**
     * The input interface.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * The input option keys.
     *
     * @var \TechDivision\Import\Utils\InputOptionKeysInterface
     */
    protected $inputOptionKeys;

    /**
     * Initializes the console options loader.
     *
     * @param \Symfony\Component\Console\Input\InputInterface     $input           The input interface
     * @param \TechDivision\Import\Utils\InputOptionKeysInterface $inputOptionKeys The interface for the input option keys
     * @param array                                               $overrideIfEmpty The input options to parse
     * @param array                                               $blacklist       The array with the blacklisted options
     */
    public function __construct(
        InputInterface $input,
        InputOptionKeysInterface $inputOptionKeys,
        array $overrideIfEmpty = array(),
        array $blacklist = array()
    ) {
        $this->input = $input;
        $this->blacklist = $blacklist;
        $this->inputOptionKeys = $inputOptionKeys;
        $this->overrideIfEmpty = $overrideIfEmpty;
    }

    /**
     * Load's the input options ans try's to initialize the configuration with the values found.
     *
     * @param \TechDivision\Import\ConfigurationInterface $instance The configuration instance to load the values for
     *
     * @return void
     */
    public function load(ConfigurationInterface $instance)
    {

        // we need the reflection properties of the configuration
        $reflectionClass = new \ReflectionClass($instance);
        $reflectionProperties = $reflectionClass->getProperties();

        // load the annoation reader
        $reader = new AnnotationReader();

        // iterate over the properties to initialize the configuration
        /** @var \ReflectionProperty $reflectionProperty */
        foreach ($reflectionProperties as $reflectionProperty) {
            // try to load the annotations of the properties
            /** @var \JMS\Serializer\Annotation\SerializedName $serializedName */
            $serializedName = $reader->getPropertyAnnotation($reflectionProperty, SerializedName::class);
            /** @var \JMS\Serializer\Annotation\SerializedName $accessors */
            $accessor = $reader->getPropertyAnnotation($reflectionProperty, Accessor::class);

            // intialize the option value (which equals the property name by default)
            $name = $reflectionProperty->getName();

            // if we've an JMS serializer annotation, we use the configured name instead
            if ($serializedName instanceof SerializedName) {
                $name = $serializedName->name;
            }

            // query whether or not the name matches an input option and is NOT on the blacklist
            if ($this->inputOptionKeys->isInputOption($name) && in_array($name, $this->blacklist) === false) {
                // query whether or not the @Accessor annotion with a setter has been specified
                if ($accessor instanceof Accessor && isset($accessor->getter) && isset($accessor->setter)) {
                    // initialize the value
                    $newValue = null;

                    // try to load the new value from the command line
                    if ($this->input->hasOption($name)) {
                        $newValue = $this->input->getOption($name);
                    }

                    // query whether or not we've a new value
                    if ($newValue === null) {
                        continue;
                    }

                    // this is the case where we may have a value from the configuration
                    // which may collate with the default value one from the command line
                    if (in_array($name, $this->overrideIfEmpty)) {
                        // first we try load the existing value from the configuration
                        $val = call_user_func(array($instance, $accessor->getter));
                        // query whether or not the command line option has REALLY been specified otherwise it'll
                        // be the default value and in that case the one from the configuration has precedence
                        if ($val === null || $this->input->hasOptionSpecified($name)) {
                            call_user_func(array($instance, $accessor->setter), $newValue);
                        }
                    } else {
                        call_user_func(array($instance, $accessor->setter), $newValue);
                    }
                }
            }
        }
    }
}
