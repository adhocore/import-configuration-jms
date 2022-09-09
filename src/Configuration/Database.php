<?php

/**
 * TechDivision\Import\Configuration\Database
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
use TechDivision\Import\Configuration\DatabaseConfigurationInterface;

/**
 * The database configuration.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 */
class Database implements DatabaseConfigurationInterface
{

    /**
     * The database identifier for this database connection.
     *
     * @var string
     * @Type("string")
     * @SerializedName("id")
     */
    protected $id;

    /**
     * The PDO DSN to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("pdo-dsn")
     */
    protected $dsn;

    /**
     * The database host to use.
     *
     * @var string
     * @Type("string")
     */
    protected $host;

    /**
     * The database port to use.
     *
     * @var string
     * @Type("integer")
     */
    protected $port;

    /**
     * The DB username to use.
     *
     * @var string
     * @Type("string")
     */
    protected $username;

    /**
     * The DB password to use.
     *
     * @var string
     * @Type("string")
     */
    protected $password;

    /**
     * The flag to signal the default datasource or not.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("default")
     */
    protected $default = false;

    /**
     * The DB timeout to use.
     *
     * @var integer
     * @Type("integer")
     */
    protected $timeout;

    /**
     * The DB version to use.
     *
     * @var string
     * @Type("string")
     */
    protected $version;

    /**
     * The DB name to use.
     *
     * @var string
     * @Type("string")
     */
    protected $name;

    /**
     * The DB table prefix to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("table-prefix")
     */
    protected $tablePrefix;

    /**
     * The DB type to use.
     *
     * @var string
     * @Type("string")
     */
    protected $type = DatabaseConfigurationInterface::TYPE_MYSQL;

    /**
     * Set's the database identifier for this database connection.
     *
     * @param string $id The database identifier
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Return's the database identifier for this database connection.
     *
     * @return string The database identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set's the PDO DSN to use.
     *
     * @param string $dsn The PDO DSN
     *
     * @return void
     */
    public function setDsn($dsn)
    {
        $this->dsn = $dsn;
    }

    /**
     * Return's the PDO DSN to use.
     *
     * @return string The PDO DSN
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * Set's the DB username to use.
     *
     * @param string $username The DB username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Return's the DB username to use.
     *
     * @return string The DB username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set's the DB password to use.
     *
     * @param string $password The DB password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Return's the DB password to use.
     *
     * @return string The DB password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set's the flag to signal that this is the default datasource or not.
     *
     * @param boolean $default TRUE if this is the default datasource, else FALSE
     *
     * @return void
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * Set's the DB table prefix to use.
     *
     * @param string $tablePrefix The DB table prefix
     *
     * @return void
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }

    /**
     * Return's the flag to signal that this is the default datasource or not.
     *
     * @return boolean TRUE if this is the default datasource, else FALSE
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Return's the DB name to use.
     *
     * @return string The DB name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return's the DB timeout to use.
     *
     * @return integer The DB timeout
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Return's the DB version to use.
     *
     * @return string The DB version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Return's the DB host to use.
     *
     * @return string The DB host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Return's the DB port to use.
     *
     * @return integer The DB port
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Return's the DB type to use.
     *
     * @return string The DB type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Return's the DB table prefix to use.
     *
     * @return string The DB table prefix
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }
}
