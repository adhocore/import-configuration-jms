<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\CsvTrait
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

/**
 * A trait implementation that provides CSV configuration handling.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
trait CsvTrait
{

    /**
     * The subject's delimiter character for CSV files.
     *
     * @var string
     * @Type("string")
     */
    protected $delimiter = ',';

    /**
     * The subject's enclosure character for CSV files.
     *
     * @var string
     * @Type("string")
     */
    protected $enclosure = '"';

    /**
     * The subject's escape character for CSV files.
     *
     * @var string
     * @Type("string")
     */
    protected $escape = '\\';

    /**
     * The subject's source charset for the CSV file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("from-charset")
     */
    protected $fromCharset;

    /**
     * The subject's target charset for a CSV file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("to-charset")
     */
    protected $toCharset;

    /**
     * The subject's file mode for a CSV target file.
     *
     * @var string
     * @Type("string")
     * @SerializedName("file-mode")
     */
    protected $fileMode;

    /**
     * Return's the delimiter character to use, default value is comma (,).
     *
     * @return string The delimiter character
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * The enclosure character to use, default value is double quotation (").
     *
     * @return string The enclosure character
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * The escape character to use, default value is backslash (\).
     *
     * @return string The escape character
     */
    public function getEscape()
    {
        return $this->escape;
    }

    /**
     * The file encoding of the CSV source file, default value is UTF-8.
     *
     * @return string The charset used by the CSV source file
     */
    public function getFromCharset()
    {
        return $this->fromCharset;
    }

    /**
     * The file encoding of the CSV targetfile, default value is UTF-8.
     *
     * @return string The charset used by the CSV target file
     */
    public function getToCharset()
    {
        return $this->toCharset;
    }

    /**
     * The file mode of the CSV target file, either one of write or append, default is write.
     *
     * @return string The file mode of the CSV target file
     */
    public function getFileMode()
    {
        return $this->fileMode;
    }
}
