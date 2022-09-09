<?php

/**
 * TechDivision\Import\Configuration\Jms\Utils\ArrayUtilInterface
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
 * Interface for utility implementations that provides custom array handling functionality.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
interface ArrayUtilInterface
{

    /**
     * Replaces the values of the first array with the ones from the arrays
     * that has been passed as additional arguments.
     *
     * @param array ...$arrays The arrays with the values that has to be replaced
     *
     * @return array The array with the replaced values
     */
    public function replace(...$arrays);
}
