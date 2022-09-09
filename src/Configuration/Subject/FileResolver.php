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
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\PostDeserialize;
use TechDivision\Import\Utils\BunchKeys;
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Configuration\Subject\FileResolverConfigurationInterface;

/**
 * The file resolver's configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class FileResolver implements FileResolverConfigurationInterface
{

    /**
     * The file resolver's Symfony DI name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_SUBJECT_FILE_RESOLVER_OK_FILE_AWARE;

    /**
     * The prefix/meta sequence of the import files.
     *
     * @var string
     * @Type("string")
     */
    protected $prefix = '.*';

    /**
     * The filename/meta sequence of the import files.
     *
     * @var string
     * @Type("string")
     */
    protected $filename = '.*';

    /**
     * The counter/meta sequence of the import files.
     *
     * @var string
     * @Type("string")
     */
    protected $counter = '\d+';

    /**
     * The file suffix for import files.
     *
     * @var string
     * @Type("string")
     */
    protected $suffix = 'csv';

    /**
     * The file suffix for OK file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("ok-file-suffix")
     */
    protected $okFileSuffix = 'ok';

    /**
     * The separator char for the elements of the file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("element-separator")
     */
    protected $elementSeparator = '_';

    /**
     * The elements to create the regex pattern that is necessary decide a subject handles a file or not.
     *
     * @var string
     * @Type("array")
     * @SerializedName("pattern-elements")
     */
    protected $patternElements = null;

    /**
     * Initialize the instance with the file resolver's Symfony DI name.
     *
     * @param string $id The Symfony DI name
     */
    public function __construct($id = DependencyInjectionKeys::IMPORT_SUBJECT_FILE_RESOLVER_OK_FILE_AWARE)
    {
        // set the Symfony DI of the file resolver
        $this->id = $id;
        // initialize the pattern elements
        $this->patternElements = BunchKeys::getAllKeys();
    }

    /**
     * Return's the file resolver's unique DI identifier.
     *
     * @return string The file resolvers's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set's the prefix/meta sequence for the import files.
     *
     * @param string $prefix The prefix
     *
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Return's the prefix/meta sequence for the import files.
     *
     * @return string The prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Return's the filename/meta sequence of the import files.
     *
     * @return string The suffix
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Return's the counter/meta sequence of the import files.
     *
     * @return string The suffix
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * Return's the suffix for the import files.
     *
     * @return string The suffix
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Return's the suffix for the OK file.
     *
     * @return string The OK file suffix
     */
    public function getOkFileSuffix()
    {
        return $this->okFileSuffix;
    }

    /**
     * Return's the delement separator char.
     *
     *  @return string The element separator char
     */
    public function getElementSeparator()
    {
        return $this->elementSeparator;
    }

    /**
     * Return's the elements the filenames consists of.
     *
     * @return array The array with the filename elements
     */
    public function getPatternElements()
    {
        return $this->patternElements;
    }

    /**
     * Lifecycle callback that will be invoked after deserialization.
     *
     * @return void
     * @PostDeserialize
     */
    public function postDeserialize()
    {

        // set the default pattern elements
        if ($this->patternElements === null) {
            $this->patternElements = BunchKeys::getAllKeys();
        }
    }
}
