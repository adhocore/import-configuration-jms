<?php

/**
 * TechDivision\Import\Configuration\Jms\Configuration
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

namespace TechDivision\Import\Configuration\Jms;

use Psr\Log\LogLevel;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\PostDeserialize;
use JMS\Serializer\Annotation\ExclusionPolicy;
use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Configuration\DatabaseConfigurationInterface;
use TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait;
use TechDivision\Import\Configuration\Jms\Configuration\CsvTrait;
use TechDivision\Import\Configuration\Jms\Configuration\ListenersTrait;
use TechDivision\Import\Configuration\ListenerAwareConfigurationInterface;
use TechDivision\Import\Configuration\OperationConfigurationInterface;

/**
 * A simple JMS based configuration implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-configuration-jms
 * @link      http://www.techdivision.com
 *
 * @ExclusionPolicy("none")
 */
class Configuration implements ConfigurationInterface, ListenerAwareConfigurationInterface
{

    /**
     * The default PID filename to use.
     *
     * @var string
     */
    const PID_FILENAME = 'importer.pid';

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\CsvTrait
     */
    use CsvTrait;

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ParamsTrait
     */
    use ParamsTrait;

    /**
     * Trait that provides CSV configuration functionality.
     *
     * @var \TechDivision\Import\Configuration\Jms\Configuration\ListenersTrait
     */
    use ListenersTrait;

    /**
     * The array with the available database types.
     *
     * @var array
     * @Exclude
     */
    protected $availableDatabaseTypes = array(
        DatabaseConfigurationInterface::TYPE_MYSQL,
        DatabaseConfigurationInterface::TYPE_REDIS
    );

    /**
     * The operation names to be executed.
     *
     * @var array
     * @Exclude
     */
    protected $operationNames = array();

    /**
     * Mapping for boolean values passed on the console.
     *
     * @var array
     * @Exclude
     */
    protected $booleanMapping = array(
        'true'  => true,
        'false' => false,
        '1'     => true,
        '0'     => false,
        'on'    => true,
        'off'   => false
    );

    /**
     * The serial that will be passed as commandline option (can not be specified in configuration file).
     *
     * @var string
     * @Exclude
     * @Accessor(setter="setSerial", getter="getSerial")
     */
    protected $serial;

    /**
     * The shortcut that maps the operation names that has to be executed.
     *
     * @var string
     * @Exclude
     */
    protected $shortcut;

    /**
     * The prefix for the move files subject.
     *
     * @var string
     * @Exclude
     * @SerializedName("move-files-prefix")
     * @Accessor(setter="setMoveFilesPrefix", getter="getMoveFilesPrefix")
     */
    protected $moveFilesPrefix;

    /**
     * The name of the command that has been invoked.
     *
     * @var string
     * @Exclude
     */
    protected $commandName;

    /**
     * The application's unique DI identifier.
     *
     * @var string
     * @Type("string")
     * @SerializedName("id")
     */
    protected $id;

    /**
     * The system name to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("system-name")
     * @Accessor(setter="setSystemName", getter="getSystemName")
     */
    protected $systemName;

    /**
     * The entity type code to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("entity-type-code")
     */
    protected $entityTypeCode;

    /**
     * The Magento installation directory.
     *
     * @var string
     * @Type("string")
     * @SerializedName("installation-dir")
     * @Accessor(setter="setInstallationDir", getter="getInstallationDir")
     */
    protected $installationDir;

    /**
     * The source directory that has to be watched for new files.
     *
     * @var string
     * @Type("string")
     * @SerializedName("source-dir")
     * @Accessor(setter="setSourceDir", getter="getSourceDir")
     */
    protected $sourceDir;

    /**
     * The target directory with the files that has been imported.
     *
     * @var string
     * @Type("string")
     * @SerializedName("target-dir")
     * @Accessor(setter="setTargetDir", getter="getTargetDir")
     */
    protected $targetDir;

    /**
     * The Magento edition, EE or CE.
     *
     * @var string
     * @Type("string")
     * @SerializedName("magento-edition")
     * @Accessor(setter="setMagentoEdition", getter="getMagentoEdition")
     */
    protected $magentoEdition = 'CE';

    /**
     * The Magento version, e. g. 2.2.0.
     *
     * @var string
     * @Type("string")
     * @SerializedName("magento-version")
     * @Accessor(setter="setMagentoVersion", getter="getMagentoVersion")
     */
    protected $magentoVersion = '2.2.0';

    /**
     * ArrayCollection with the information of the configured databases.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\Database>")
     */
    protected $databases;

    /**
     * ArrayCollection with the information of the configured loggers.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<string, TechDivision\Import\Configuration\Jms\Configuration\Logger>")
     */
    protected $loggers;

    /**
     * The subject's multiple field delimiter character for fields with multiple values, defaults to (,).
     *
     * @var string
     * @Type("string")
     * @SerializedName("multiple-field-delimiter")
     */
    protected $multipleFieldDelimiter = ',';

    /**
     * The subject's multiple value delimiter character for fields with multiple values, defaults to (|).
     *
     * @var string
     * @Type("string")
     * @SerializedName("multiple-value-delimiter")
     */
    protected $multipleValueDelimiter = '|';

    /**
     * The flag to signal that the subject has to use the strict mode or not.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("strict-mode")
     */
    protected $strictMode;

    /**
     * The flag whether or not the import artefacts have to be archived.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("archive-artefacts")
     * @Accessor(setter="setArchiveArtefacts", getter="haveArchiveArtefacts")
     */
    protected $archiveArtefacts;

    /**
     * The directory where the archives will be stored.
     *
     * @var string
     * @Type("string")
     * @SerializedName("archive-dir")
     * @Accessor(setter="setArchiveDir", getter="getArchiveDir")
     */
    protected $archiveDir;

    /**
     * The flag to signal that the subject has to use the debug mode or not.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("debug-mode")
     * @Accessor(setter="setDebugMode", getter="isDebugMode")
     */
    protected $debugMode = false;

    /**
     * The log level to use (see Monolog documentation).
     *
     * @var string
     * @Type("string")
     * @SerializedName("log-level")
     * @Accessor(setter="setLogLevel", getter="getLogLevel")
     */
    protected $logLevel = LogLevel::INFO;

    /**
     * The explicit DB ID to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("use-db-id")
     */
    protected $useDbId;

    /**
     * The explicit PID filename to use.
     *
     * @var string
     * @Type("string")
     * @SerializedName("pid-filename")
     * @Accessor(setter="setPidFilename", getter="getPidFilename")
     */
    protected $pidFilename;

    /**
     * The collection with the paths to additional vendor directories.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\VendorDir>")
     * @SerializedName("additional-vendor-dirs")
     */
    protected $additionalVendorDirs;

    /**
     * ArrayCollection with the information of the configured operations.
     *
     * @var array
     * @Type("array<string, array<string, ArrayCollection<string, TechDivision\Import\Configuration\Jms\Configuration\Operation>>>")
     */
    protected $operations = array();

    /**
     * The array with the Magento Edition specific extension libraries.
     *
     * @var array
     * @Type("array")
     * @SerializedName("extension-libraries")
     */
    protected $extensionLibraries = array();

    /**
     * The array with the custom header mappings.
     *
     * @var array
     * @SerializedName("header-mappings")
     * @Type("array<string, array<string, string>>")
     */
    protected $headerMappings = array();

    /**
     * The array with the custom image types.
     *
     * @var array
     * @Type("array")
     * @SerializedName("image-types")
     */
    protected $imageTypes = array();

    /**
     * The flag to signal that the import should be wrapped within a single transation or not.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("single-transaction")
     * @Accessor(setter="setSingleTransaction", getter="isSingleTransaction")
     */
    protected $singleTransaction = false;

    /**
     * The flag to signal that the cache should be enabled or not.
     *
     * @var boolean
     * @Type("boolean")
     * @SerializedName("cache-enabled")
     * @Accessor(setter="setCacheEnabled", getter="isCacheEnabled")
     */
    protected $cacheEnabled = true;

    /**
     * ArrayCollection with the information of the configured aliases.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\Alias>")
     */
    protected $aliases;

    /**
     * ArrayCollection with the information of the configured caches.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Type("ArrayCollection<TechDivision\Import\Configuration\Jms\Configuration\Cache>")
     */
    protected $caches;

    /**
     * The array with the shortcuts.
     *
     * @var array
     * @Type("array<string, array<string, array>>")
     * @SerializedName("shortcuts")
     */
    protected $shortcuts = array();

    /**
     * The username to save the import history with.
     *
     * @var string
     * @Type("string")
     */
    protected $username;

    /**
     * The array with the finder mappings.
     *
     * @var array
     * @SerializedName("finder-mappings")
     * @Type("array<string, string>")
     * @Accessor(setter="setFinderMappings", getter="getFinderMappings")
     */
    protected $finderMappings = array();

    /**
     * The array with the default values.
     *
     * @var array
     * @SerializedName("default-values")
     * @Type("array<string, array<string, string>>")
     * @Accessor(setter="setDefaultValues", getter="getDefaultValues")
     */
    protected $defaultValues = array();

    /**
     * Lifecycle callback that will be invoked after deserialization.
     *
     * @return void
     * @PostDeserialize
     */
    public function postDeserialize()
    {

        // create an empty collection if no loggers has been specified
        if ($this->loggers === null) {
            $this->loggers = new ArrayCollection();
        }

        // create an empty collection if no caches has been specified
        if ($this->caches === null) {
            $this->caches = new ArrayCollection();
        }

        // create an empty collection if no aliases has been specified
        if ($this->aliases === null) {
            $this->aliases = new ArrayCollection();
        }

        // create an empty collection if no databases has been specified
        if ($this->databases === null) {
            $this->databases = new ArrayCollection();
        }

        // create an empty collection if no additional venor directories has been specified
        if ($this->additionalVendorDirs === null) {
            $this->additionalVendorDirs = new ArrayCollection();
        }
    }

    /**
     * Map's the passed value to a boolean.
     *
     * @param string $value The value to map
     *
     * @return boolean The mapped value
     * @throws \Exception Is thrown, if the value can't be mapped
     */
    public function mapBoolean($value)
    {

        // do nothing, because passed value is already a boolean
        if (is_bool($value)) {
            return $value;
        }

        // try to map the passed value to a boolean
        if (isset($this->booleanMapping[$val = strtolower($value)])) {
            return $this->booleanMapping[$val];
        }

        // throw an exception if we can't convert the passed value
        throw new \Exception(sprintf('Can\'t convert %s to boolean', $value));
    }

    /**
     * Return's the application's unique DI identifier.
     *
     * @return string The application's unique DI identifier
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add's the operation with the passed name ot the operations that has to be executed.
     *
     * If the operation name has already been added, it'll not be added again.
     *
     * @param string  $operationName The operation to be executed
     * @param boolean $prepend       TRUE if the operation name should be prepended, else FALSE
     *
     * @return void
     */
    public function addOperationName($operationName, $prepend = false)
    {

        // do nothing if the operation has already been added
        if (in_array($operationName, $this->operationNames)) {
            return;
        }

        // add the operation otherwise
        $prepend ? array_unshift($this->operationNames, $operationName) : array_push($this->operationNames, $operationName);
    }

    /**
     * Return's the operation names that has to be executed.
     *
     * @param array $operationNames The operation names that has to be executed
     *
     * @return void
     */
    public function setOperationNames(array $operationNames)
    {
        return $this->operationNames = $operationNames;
    }

    /**
     * Return's the operation names that has to be executed.
     *
     * @return array The operation names that has to be executed
     */
    public function getOperationNames()
    {
        return $this->operationNames;
    }

    /**
     * Queries whether or not the passed operation has to be exceuted or not.
     *
     * @param \TechDivision\Import\Configuration\OperationConfigurationInterface $operation The operation to query for
     *
     * @return boolean TRUE if the operation has to be executed, else FALSE
     */
    public function inOperationNames(OperationConfigurationInterface $operation)
    {
        return in_array($operation->getName(), $this->getOperationNames());
    }

    /**
     * Set's the Magento installation directory.
     *
     * @param string $installationDir The Magento installation directory
     *
     * @return void
     */
    public function setInstallationDir($installationDir)
    {
        $this->installationDir = $installationDir;
    }

    /**
     * Return's the Magento installation directory.
     *
     * @return string The Magento installation directory
     */
    public function getInstallationDir()
    {
        return $this->installationDir;
    }

    /**
     * Set's the source directory that has to be watched for new files.
     *
     * @param string $sourceDir The source directory
     *
     * @return void
     */
    public function setSourceDir($sourceDir)
    {
        $this->sourceDir = $sourceDir;
    }

    /**
     * Return's the source directory that has to be watched for new files.
     *
     * @return string The source directory
     */
    public function getSourceDir()
    {
        return $this->sourceDir;
    }

    /**
     * Set's the target directory with the files that has been imported.
     *
     * @param string $targetDir The target directory
     *
     * @return void
     */
    public function setTargetDir($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * Return's the target directory with the files that has been imported.
     *
     * @return string The target directory
     */
    public function getTargetDir()
    {
        return $this->targetDir;
    }

    /**
     * Set's the Magento edition, EE or CE.
     *
     * @param string $magentoEdition The Magento edition
     *
     * @return void
     */
    public function setMagentoEdition($magentoEdition)
    {
        $this->magentoEdition = $magentoEdition;
    }

    /**
     * Return's the Magento edition, EE or CE.
     *
     * @return string The Magento edition
     */
    public function getMagentoEdition()
    {
        return $this->magentoEdition;
    }

    /**
     * Return's the Magento version, e. g. 2.1.0.
     *
     * @param string $magentoVersion The Magento version
     *
     * @return void
     */
    public function setMagentoVersion($magentoVersion)
    {
        $this->magentoVersion = $magentoVersion;
    }

    /**
     * Return's the Magento version, e. g. 2.1.0.
     *
     * @return string The Magento version
     */
    public function getMagentoVersion()
    {
        return $this->magentoVersion;
    }

    /**
     * Return's the entity type code to be used.
     *
     * @return string The entity type code to be used
     */
    public function getEntityTypeCode()
    {
        return $this->entityTypeCode;
    }

    /**
     * Set's the entity type code to be used.
     *
     * @param string $entityTypeCode The entity type code
     *
     * @return void
     */
    public function setEntityTypeCode($entityTypeCode)
    {
        $this->entityTypeCode = $entityTypeCode;
    }

    /**
     * Return's the multiple field delimiter character to use, default value is comma (,).
     *
     * @return string The multiple field delimiter character
     */
    public function getMultipleFieldDelimiter()
    {
        return $this->multipleFieldDelimiter;
    }

    /**
     * Return's the multiple value delimiter character to use, default value is comma (|).
     *
     * @return string The multiple value delimiter character
     */
    public function getMultipleValueDelimiter()
    {
        return $this->multipleValueDelimiter;
    }

    /**
     * Queries whether or not strict mode is enabled or not, default is TRUE.
     *
     * @return boolean TRUE if strict mode is enabled, else FALSE
     */
    public function isStrictMode()
    {
        return $this->strictMode;
    }

    /**
     * Remove's all configured database configuration.
     *
     * @return void
     */
    public function clearDatabases()
    {
        $this->databases->clear();
    }

    /**
     * Add's the passed database configuration.
     *
     * @param \TechDivision\Import\Configuration\DatabaseConfigurationInterface $database The database configuration
     *
     * @return void
     */
    public function addDatabase(DatabaseConfigurationInterface $database)
    {
        $this->databases->add($database);
    }

    /**
     * Return's the number database configurations.
     *
     * @return integer The number of database configurations
     */
    public function countDatabases()
    {
        return $this->databases->count();
    }

    /**
     * Return's the database configuration with the passed ID.
     *
     * @param string $id The ID of the database connection to return
     *
     * @return \TechDivision\Import\Configuration\DatabaseConfigurationInterface The database configuration
     * @throws \Exception Is thrown, if no database configuration is available
     */
    public function getDatabaseById($id)
    {

        // iterate over the configured databases and return the one with the passed ID
        /** @var \TechDivision\Import\Configuration\DatabaseConfigurationInterface $database */
        foreach ($this->databases as $database) {
            if ($database->getId() === $id && $this->isValidDatabaseType($database)) {
                return $database;
            }
        }

        // throw an exception, if the database with the passed ID is NOT configured
        throw new \Exception(sprintf('Database with ID %s can not be found or has an invalid type', $id));
    }

    /**
     * Return's the databases for the given type.
     *
     * @param string $type The database type to return the configurations for
     *
     * @return \Doctrine\Common\Collections\Collection The collection with the database configurations
     */
    public function getDatabasesByType($type)
    {

        // initialize the collection for the database configurations
        $databases = new ArrayCollection();

        // iterate over the configured databases and return the one with the passed ID
        /** @var \TechDivision\Import\Configuration\DatabaseConfigurationInterface  $database */
        foreach ($this->databases as $database) {
            if ($database->getType() === $type && $this->isValidDatabaseType($database)) {
                $databases->add($database);
            }
        }

        // return the database configurations
        return $databases;
    }

    /**
     * Query's whether or not the passed database configuration has a valid type.
     *
     * @param \TechDivision\Import\Configuration\DatabaseConfigurationInterface $database The database configuration
     *
     * @return boolean TRUE if the passed database configuration has a valid type, else FALSE
     */
    protected function isValidDatabaseType(DatabaseConfigurationInterface $database)
    {
        return in_array(strtolower($database->getType()), $this->availableDatabaseTypes);
    }

    /**
     * Return's the database configuration.
     *
     * If an explicit DB ID is specified, the method tries to return the database with this ID. If
     * the database configuration is NOT available, an execption is thrown.
     *
     * If no explicit DB ID is specified, the method tries to return the default database configuration,
     * if not available the first one.
     *
     * @return \TechDivision\Import\Configuration\DatabaseConfigurationInterface The database configuration
     * @throws \Exception Is thrown, if no database configuration is available
     */
    public function getDatabase()
    {

        // if a DB ID has been set, try to load the database
        if ($useDbId = $this->getUseDbId()) {
            return $this->getDatabaseById($useDbId);
        }

        // iterate over the configured databases and try return the default database
        /** @var \TechDivision\Import\Configuration\DatabaseConfigurationInterface  $database */
        foreach ($this->databases as $database) {
            if ($database->isDefault() && $this->isValidDatabaseType($database)) {
                return $database;
            }
        }

        // try to return the first database configurtion
        if ($this->databases->count() > 0) {
            return $this->databases->first();
        }

        // throw an exception, if no database configuration is available
        throw new \Exception('There is no database configuration available');
    }

    /**
     * Return's the array with the configured operations.
     *
     * @return array The array with the operations
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Return's the ArrayCollection with the configured shortcuts.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The ArrayCollection with the shortcuts
     */
    public function getShortcuts()
    {
        return $this->shortcuts;
    }

    /**
     * Return's the ArrayCollection with the configured loggers.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The ArrayCollection with the loggers
     */
    public function getLoggers()
    {
        return $this->loggers;
    }

    /**
     * Set's the flag that import artefacts have to be archived or not.
     *
     * @param mixed $archiveArtefacts TRUE if artefacts have to be archived, else FALSE
     *
     * @return void
     */
    public function setArchiveArtefacts($archiveArtefacts)
    {
        $this->archiveArtefacts = $this->mapBoolean($archiveArtefacts);
    }

    /**
     * Return's the TRUE if the import artefacts have to be archived.
     *
     * @return boolean TRUE if the import artefacts have to be archived
     */
    public function haveArchiveArtefacts()
    {
        return $this->archiveArtefacts;
    }

    /**
     * The directory where the archives will be stored.
     *
     * @param string $archiveDir The archive directory
     *
     * @return void
     */
    public function setArchiveDir($archiveDir)
    {
        $this->archiveDir = $archiveDir;
    }

    /**
     * The directory where the archives will be stored.
     *
     * @return string The archive directory
     */
    public function getArchiveDir()
    {
        return $this->archiveDir;
    }

    /**
     * Set's the debug mode.
     *
     * @param mixed $debugMode TRUE if debug mode is enabled, else FALSE
     *
     * @return void
     */
    public function setDebugMode($debugMode)
    {
        $this->debugMode = $this->mapBoolean($debugMode);
    }

    /**
     * Queries whether or not debug mode is enabled or not, default is TRUE.
     *
     * @return boolean TRUE if debug mode is enabled, else FALSE
     */
    public function isDebugMode()
    {
        return $this->debugMode;
    }

    /**
     * Set's the log level to use.
     *
     * @param string $logLevel The log level to use
     *
     * @return void
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * Return's the log level to use.
     *
     * @return string The log level to use
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * Set's the explicit DB ID to use.
     *
     * @param string $useDbId The explicit DB ID to use
     *
     * @return void
     */
    public function setUseDbId($useDbId)
    {
        $this->useDbId = $useDbId;
    }

    /**
     * Return's the explicit DB ID to use.
     *
     * @return string The explicit DB ID to use
     */
    public function getUseDbId()
    {
        return $this->useDbId;
    }

    /**
     * Set's the PID filename to use.
     *
     * @param string $pidFilename The PID filename to use
     *
     * @return void
     */
    public function setPidFilename($pidFilename)
    {
        $this->pidFilename = $pidFilename;
    }

    /**
     * Return's the PID filename to use.
     *
     * @return string The PID filename to use
     */
    public function getPidFilename()
    {
        return $this->pidFilename;
    }

    /**
     * Set's the systemm name to be used.
     *
     * @param string $systemName The system name to be used
     *
     * @return void
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
    }

    /**
     * Return's the systemm name to be used.
     *
     * @return string The system name to be used
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * Set's the collection with the path of the Magento Edition specific extension libraries.
     *
     * @param array $extensionLibraries The paths of the Magento Edition specific extension libraries
     *
     * @return void
     */
    public function setExtensionLibraries(array $extensionLibraries)
    {
        $this->extensionLibraries = $extensionLibraries;
    }

    /**
     * Return's an array with the path of the Magento Edition specific extension libraries.
     *
     * @return array The paths of the Magento Edition specific extension libraries
     */
    public function getExtensionLibraries()
    {
        return $this->extensionLibraries;
    }

    /**
     * Return's a collection with the path to additional vendor directories.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The paths to additional vendor directories
     */
    public function getAdditionalVendorDirs()
    {
        return $this->additionalVendorDirs;
    }

    /**
     * The array with the subject's custom header mappings.
     *
     * @return array The custom header mappings
     */
    public function getHeaderMappings()
    {
        return $this->headerMappings;
    }

    /**
     * The array with the subject's custom image types.
     *
     * @return array The custom image types
     */
    public function getImageTypes()
    {
        return $this->imageTypes;
    }

    /**
     * Set's the flag that decides whether or not the import should be wrapped within a single transaction.
     *
     * @param mixed $singleTransaction TRUE if the import should be wrapped in a single transation, else FALSE
     *
     * @return void
     */
    public function setSingleTransaction($singleTransaction)
    {
        $this->singleTransaction = $this->mapBoolean($singleTransaction);
    }

    /**
     * Whether or not the import should be wrapped within a single transation.
     *
     * @return boolean TRUE if the import should be wrapped in a single transation, else FALSE
     */
    public function isSingleTransaction()
    {
        return $this->singleTransaction;
    }

    /**
     * Set's the flag that decides whether or not the the cache has been enabled.
     *
     * @param mixed $cacheEnabled TRUE if the cache has been enabled, else FALSE
     *
     * @return void
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $this->mapBoolean($cacheEnabled);
    }

    /**
     * Whether or not the cache functionality should be enabled.
     *
     * @return boolean TRUE if the cache has to be enabled, else FALSE
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }

    /**
     * Set's the passed serial from the commandline to the configuration.
     *
     * @param string $serial The serial from the commandline
     *
     * @return void
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    /**
     * Return's the serial from the commandline.
     *
     * @return string The serial
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Return's the configuration for the caches.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The cache configurations
     */
    public function getCaches()
    {

        // iterate over the caches and set the parent configuration instance
        foreach ($this->caches as $cache) {
            $cache->setConfiguration($this);
        }

        // return the array with the caches
        return $this->caches;
    }

    /**
     * Return's the cache configuration for the passed type.
     *
     * @param string $type The cache type to return the configuation for
     *
     * @return \TechDivision\Import\Configuration\CacheConfigurationInterface The cache configuration
     */
    public function getCacheByType($type)
    {

        // load the available cache configurations
        $caches = $this->getCaches();

        // try to load the cache for the passed type
        /** @var \TechDivision\Import\Configuration\CacheConfigurationInterface $cache */
        foreach ($caches as $cache) {
            if ($cache->getType() === $type) {
                return $cache;
            }
        }
    }

    /**
     * Return's the alias configuration.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection The alias configuration
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Set's the prefix for the move files subject.
     *
     * @param string $moveFilesPrefix The prefix for the move files subject
     *
     * @return void
     */
    public function setMoveFilesPrefix($moveFilesPrefix)
    {
        $this->moveFilesPrefix = $moveFilesPrefix;
    }

    /**
     * Return's the prefix for the move files subject.
     *
     * @return string The prefix for the move files subject
     */
    public function getMoveFilesPrefix()
    {
        return $this->moveFilesPrefix;
    }

    /**
     * Set's the shortcut that maps the operation names that has to be executed.
     *
     * @param string $shortcut The shortcut
     *
     * @return void
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;
    }

    /**
     * Return's the shortcut that maps the operation names that has to be executed.
     *
     * @return string The shortcut
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * Set's the name of the command that has been invoked.
     *
     * @param string $commandName The command name
     *
     * @return void
     */
    public function setCommandName($commandName)
    {
        $this->commandName = $commandName;
    }

    /**
     * Return's the name of the command that has been invoked.
     *
     * @return string The command name
     */
    public function getCommandName()
    {
        return $this->commandName;
    }

    /**
     * Set's the username to save the import history with.
     *
     * @param string $username The username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Return's the username to save the import history with.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set's the array with the finder mappings.
     *
     * @param array $finderMappings The finder mappings
     *
     * @return void
     */
    public function setFinderMappings(array $finderMappings)
    {

        // convert the finder mappings keys, which are constants, to their values
        foreach ($finderMappings as $key => $value) {
            $this->finderMappings[defined($key) ? constant($key) : $key] = $value;
        }
    }

    /**
     * Return's the array with the finder mappings.
     *
     * @return array The finder mappings
     */
    public function getFinderMappings()
    {
        return $this->finderMappings;
    }

    /**
     * Return's the mapped finder for the passed key.
     *
     * @param string $key The key of the finder to map
     *
     * @return string The mapped finder name
     * @throws \InvalidArgumentException Is thrown if the mapping with passed key can not be resolved
     */
    public function getFinderMappingByKey($key)
    {

        // try to resolve the mapping for the finder with the passed key
        if (isset($this->finderMappings[$key])) {
            return $this->finderMappings[$key];
        }

        // throw an exception otherwise
        throw new \InvalidArgumentException(sprintf('Can\'t load mapping for finder with key "%s"', $key));
    }

    /**
     * Sets the default values from the configuration.
     *
     * @param array $defaultValues The array with the default values
     *
     * @return void
     */
    public function setDefaultValues(array $defaultValues)
    {
        $this->defaultValues = $defaultValues;
    }

    /**
     * Load the default values from the configuration.
     *
     * @return array The array with the default values
     */
    public function getDefaultValues()
    {
        return $this->defaultValues;
    }

    /**
     * Return's an unique array with the prefixes of all configured subjects.
     *
     * @param array $ignore An array with prefixes that has to be ignored
     *
     * @return array An array with the available prefixes
     */
    public function getPrefixes($ignore = array('.*'))
    {

        // initialize the array for the prefixes
        $prefixes = array();

        foreach ($this->getOperations() as $operation) {
            foreach ($operation as $entityTypes) {
                foreach ($entityTypes as $operationConfiguration) {
                    foreach ($operationConfiguration->getPlugins() as $plugin) {
                        foreach ($plugin->getSubjects() as $subject) {
                            // ignore the prefix, if it has already been added or it has to be ignored
                            if (in_array($prefix = $subject->getPrefix(), $prefixes, true) ||
                                in_array($prefix, $ignore, true)
                            ) {
                                continue;
                            }

                            // add the prefix to the list
                            $prefixes[] = $prefix;
                        }
                    }
                }
            }
        }

        // return the array with the unique prefixes
        return $prefixes;
    }
}
