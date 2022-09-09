<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration\SwiftMailer
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
use TechDivision\Import\Configuration\SwiftMailerConfigurationInterface;

/**
 * The swift mailer configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class SwiftMailer implements SwiftMailerConfigurationInterface
{

    /**
     * The trait that provides parameter configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * The DI ID used to create the swift mailer instance.
     *
     * @var string
     * @Type("string")
     */
    protected $id;

    /**
     * The swift mailer transport configuration to use.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\SwiftMailer\Transport
     * @Type("TechDivision\Import\Configuration\Jms\Configuration\SwiftMailer\Transport")
     */
    protected $transport;

    /**
     * Return's the DI ID used to create the swift mailer instance.
     *
     * @return string The DI ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the swift mailer transport configuration to use.
     *
     * @return \TechDivision\Import\Configuration\Jms\Configuration\SwiftMailer\Transport The transport configuration to use
     */
    public function getTransport()
    {
        return $this->transport;
    }
}
