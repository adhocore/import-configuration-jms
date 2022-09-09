<?php

/**
 * TechDivision\Import\Configuration\Jms\Utils\ArrayUtil
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

namespace TechDivision\Import\Configuration\Jms\Utils;

/**
 * Utility class that provides custom array handling functionality.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class ArrayUtil implements ArrayUtilInterface
{

    /**
     * Replaces the values of the first array with the ones from the arrays
     * that has been passed as additional arguments.
     *
     * @param array ...$arrays The arrays with the values that replace the first one
     *
     * @return array The array with the replaced values
     */
    public function replace(...$arrays)
    {

        // load the passed arrays
        $values = func_get_args();

        // initialize the main array the others should be merged into
        $main = array();

        // query whether we've more then one arrays passed
        if (sizeof($values) > 1) {
            // if yes, the first one is the main array
            $main = array_shift($values);
        }

        // iterate over the other arrays and replace the
        // values of the main array with their values
        while ($arr = array_shift($values)) {
            // iterate over the arrays attributes
            foreach ($arr as $key => $src) {
                // query whether the attribute is an array or not, if yes we
                // have to replace the values of the main attribute with the
                // values of the actual array recursively
                if (is_array($src)) {
                    // initialize the array to merge the values into
                    $dest = array();

                    // override the array with the values of the main array if
                    // the actual attribute also contains arrays itself
                    if ($this->isBranch($src)) {
                        $dest = isset($main[$key]) ? $main[$key] : array();
                    }

                    // finally replace the values in the main attribute
                    $main[$key] = $this->replace($dest, $src);
                } else {
                    // simply override the values of the main array
                    $main[$key] = $src;
                }
            }
        }

        // return the array with the replaced values
        return $main;
    }

    /**
     * Query's whether or noth the passed node is a branch or a leaf.
     *
     * @param array $node The node to query for
     *
     * @return boolean TRUE if the node is a branch, else FALSE
     */
    protected function isBranch(array $node)
    {

        // iterate over th nodes attributes and query whether
        // or not at least one of it is an array
        foreach ($node as $attr) {
            if (is_array($attr)) {
                return true;
            }
        }

        // return false if the node only contains scalar values
        return false;
    }
}
