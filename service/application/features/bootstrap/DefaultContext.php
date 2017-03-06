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
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel    = $kernel;
        $this->container = $kernel->getContainer();
    }

    /**
     * @param array              $fixtures
     * @param bool|false         $keepDatabase
     */
    public function __construct($fixtures = array(), $keepDatabase = false)
    {
        $this->fixtures           = $fixtures;
        $this->keepDatabase       = $keepDatabase;
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
        if (count($this->fixtures) === 0) {
            return;
        }

        if ($this->keepDatabase && in_array('keep-database', $scope->getScenario()->getTags(), true) === true) {
            return;
        }


        $this->getDocumentManager()->getSchemaManager()->dropDatabases();


        // TODO: Fix the problem with loading fixtures and find an alternative to h4cc_alice_fixtures

        /*

        $manager = $this->container->get('h4cc_alice_fixtures.manager');
        $objects = $manager->loadFiles($this->fixtures, 'yaml');
        $manager->persist($objects, true);

        */
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

    /**
     * @param string|null $filename
     *
     * @return mixed
     */
    protected function getJson($filename = null)
    {
        $json = $this->getSession()->getDriver()->getContent();

        $schema = null;

        if ($filename !== null) {

            $retriever = new UriRetriever();
            $schema    = $retriever->retrieve('file://' . getcwd() . '/' . $filename);

            $refResolver           = new RefResolver($retriever);
            RefResolver::$maxDepth = 10;
            $refResolver->resolve($schema, 'file://' . getcwd() . '/' . $filename);
        }

        $jsonDecoder = new JsonDecoder();

        return $jsonDecoder->decode($json, $schema);
    }
}