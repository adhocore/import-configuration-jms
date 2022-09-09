<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\Alias
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
use TechDivision\Import\Configuration\AliasConfigurationInterface;

/**
 * The alias configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Alias implements AliasConfigurationInterface
{

    /**
     * The alias's unique DI identifier.
     *
     * @var string
     * @Type()("string")
     * @SerializedName("id")
     */
    protected $id;

    /**
     * The alias's target ID identifier.
     *
     * @var string
     * @Type("string")
     */
    protected $target;

    /**
     * Return's the alias unique DI identifier
     *
     * @return string The alias unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the alias target DI identifier.
     *
     * @return string The alias target ID identifier
     */
    public function getTarget()
    {
        return $this->target;
    }
}
