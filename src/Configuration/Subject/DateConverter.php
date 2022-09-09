<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\DateConverter
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
use TechDivision\Import\Utils\DependencyInjectionKeys;
use TechDivision\Import\Configuration\Subject\DateConverterConfigurationInterface;

/**
 * A simple date converter configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class DateConverter implements DateConverterConfigurationInterface
{

    /**
     * The date converter's class name.
     *
     * @var string
     * @Type("string")
     */
    protected $id = DependencyInjectionKeys::IMPORT_SUBJECT_DATE_CONVERTER_SIMPLE;

    /**
     * The source date format to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("source-date-format")
     */
    protected $sourceDateFormat = 'n/d/y, g:i A';

    /**
     * Returns the date converter's unique DI identifier.
     *
     * @return string The date converter's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the source date format to use.
     *
     * @return string The source date format
     */
    public function getSourceDateFormat()
    {
        return $this->sourceDateFormat;
    }
}
