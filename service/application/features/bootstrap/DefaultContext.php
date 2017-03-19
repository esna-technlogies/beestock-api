<?php

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\ODM\MongoDB\DocumentManager;
use JsonSchema\RefResolver;
use JsonSchema\Uri\UriRetriever;
use Behatch\Context\RestContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Json\JsonDecoder;

class DefaultContext extends RawMinkContext implements \Behat\Behat\Context\Context, KernelAwareContext
{
    /**
     * @var
     */
    private static $placeholders = array();

    /**
     * @var array
     */
    private $fixtures;

    /**
     * @var bool|false
     */
    private $keepDatabase;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RestContext
     */
    protected $restContext;

    /**
     * @param array              $fixtures
     * @param bool|false         $keepDatabase
     */
    public function __construct($fixtures = array(), $keepDatabase = false)
    {
        $this->fixtures      = $fixtures;
        $this->keepDatabase  = $keepDatabase;
    }

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel    = $kernel;
        $this->container = $kernel->getContainer();
    }

    /** @BeforeSuite */
    public static function setup(\Behat\Testwork\Hook\Scope\BeforeSuiteScope $scope)
    {
        // TODO
    }

    /** @AfterSuite */
    public static function teardown(\Behat\Testwork\Hook\Scope\AfterSuiteScope $scope)
    {
        // TODO
    }

    /** @BeforeFeature */
    public static function setupFeature(\Behat\Behat\Hook\Scope\BeforeFeatureScope $scope)
    {
        // TODO
    }

    /** @AfterFeature */
    public static function teardownFeature(\Behat\Behat\Hook\Scope\AfterFeatureScope $scope)
    {
        // TODO
    }


    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
        // TODO
    }

    /** @AfterScenario */
    public function after(\Behat\Behat\Hook\Scope\AfterScenarioScope $scope)
    {
        // TODO
    }


    /**
     * @param BeforeScenarioScope $scope
     *
     * @BeforeScenario
     */
    public function prepareContext(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $env */
        $env                = $scope->getEnvironment();
        $this->restContext  = $env->getContext(RestContext::class);
        self::$placeholders = [];
    }

    /**
     * @param BeforeScenarioScope $scope
     *
     * @BeforeScenario
     */
    public function loadFixtures(BeforeScenarioScope $scope)
    {

        if ($this->keepDatabase && in_array('keep-database', $scope->getFeature()->getTags(), true) === true) {
            return;
        }

        $this->getDocumentManager()->getSchemaManager()->dropDatabases();

        $this->getDocumentManager()->getSchemaManager()->createDatabases();
    }

    /**
     * Replaces placeholders in provided text.
     *
     * @param string $string
     *
     * @return string
     */
    protected function replacePlaceholder($string)
    {
        return str_replace(array_keys(self::$placeholders), self::$placeholders, $string);
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function setPlaceholder($key, $value)
    {
        self::$placeholders[$key] = $value;
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->container->get('doctrine_mongodb')->getManager();
    }
}