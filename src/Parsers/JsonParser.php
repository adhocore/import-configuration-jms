<?php

/**
 * TechDivision\Import\Configuration\Jms\Parsers\JsonParser
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

namespace TechDivision\Import\Configuration\Jms\Parsers;

use TechDivision\Import\Configuration\Jms\Iterators\DirnameFilter;
use TechDivision\Import\Configuration\Jms\Iterators\FilenameFilter;
use TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface;
use TechDivision\Import\Configuration\Jms\ConfigurationParserInterface;

/**
 * The JSON configuration parser implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class JsonParser implements ConfigurationParserInterface
{

    /**
     * The utility class that provides array handling functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface
     */
    protected $arrayUtil;

    /**
     * Initializes the parser with the array utility instance.
     *
     * @param \TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface $arrayUtil The utility instance
     */
    public function __construct(ArrayUtilInterface $arrayUtil)
    {
        $this->arrayUtil = $arrayUtil;
    }

    /**
     * Parsing the configuration and merge it recursively.
     *
     * @param array $directories An array with diretories to parse
     *
     * @return void
     * @throws \Exception Is thrown if the configuration can not be loaded from the configuration files
     */
    public function parse(array $directories)
    {

        // initialize the array that'll contain the configuration structure
        $main = array();

        // iterate over the found directories to parse them for configuration files
        foreach ($directories as $directory) {
            // load the configuration filenames
            $filenames = $this->listContents($directory, 'json');

            // load the content of each found configuration file and merge it
            foreach ($filenames as $filename) {
                if (is_file($filename) && $content = json_decode(file_get_contents($filename), true)) {
                    $main = $this->replace($main, $content);
                } else {
                    throw new \Exception(sprintf('Can\'t load content of file %s', $filename));
                }
            }
        }

        // return the JSON encoded configuration
        return json_encode($main, JSON_PRETTY_PRINT);
    }

    /**
     * Return's the array utility instance.
     *
     * @return \TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface The utility instance
     */
    protected function getArrayUtil()
    {
        return $this->arrayUtil;
    }

    /**
     * Replaces the values of the first array with the ones from the arrays
     * that has been passed as additional arguments.
     *
     * @param array ...$arrays The arrays with the values that has to be replaced
     *
     * @return array The array with the replaced values
     */
    protected function replace(...$arrays)
    {
        return $this->getArrayUtil()->replace(...$arrays);
    }

    /**
     * List the filenames of a directory.
     *
     * @param string $directory The directory to list
     * @param string $suffix    The suffix of the files to list
     *
     * @return array A list of filenames
     */
    protected function listContents($directory = '', $suffix = '.*')
    {

        // initialize the recursive directory iterator
        $directory = new \RecursiveDirectoryIterator($directory);
        $directory->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);

        // initialize the filters for file- and dirname
        $filter = new DirnameFilter($directory, '/^(?!\.Trash)/');
        $filter = new FilenameFilter($directory, sprintf('/\.(?:%s)$/', $suffix));

        // initialize the array for the files
        $files = array();

        // load the files
        foreach (new \RecursiveIteratorIterator($filter) as $file) {
            array_unshift($files, $file);
        }

        // sort the files ascending
        usort($files, function ($a, $b) {
            return strcmp($a, $b);
        });

        // return the array with the files
        return $files;
    }
}
