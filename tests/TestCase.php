<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace yiiunit\extensions\elfinder;

use Yii;
use yii\base\Action;
use yii\base\Module;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * This is the base class for all yii framework unit tests.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    /**
     * @param array $config
     * @param string $appClass
     */
    protected function mockWebApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'modules' => [
                'elfinder' => [
                    'class' => '\simialbi\yii2\elfinder\Module',
                    'options' => [],
                    'connectionSets' => []
                ]
            ],
            'aliases' => [
                '@bower' => '@vendor/bower-asset',
                '@npm' => '@vendor/npm-asset',
            ],
            'components' => [
                'request' => [
                    'cookieValidationKey' => '2VYuNNIognPSVv0zqj1C9sdmgk_O1UBa',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                ],
                'ews' => [
                    'class' => 'simialbi\yii2\ews\Connection'
                ],
                'log' => [
                    'traceLevel' => YII_DEBUG ? 3 : 0,
                    'targets' => [
                        [
                            'class' => 'yii\log\FileTarget',
                            'levels' => ['error', 'warning', 'info'],
                        ],
                    ],
                ],
                'urlManager' => [
                    'showScriptName' => true,
                ],
            ],
            'params' => [
                'adminEmail' => 'admin@example.com',
            ],
        ], $config));
    }

    /**
     * Mocks controller action with parameters
     *
     * @param string $controllerId
     * @param string $actionID
     * @param string $moduleID
     * @param array $params
     */
    protected function mockAction($controllerId, $actionID, $moduleID = null, $params = [])
    {
        Yii::$app->controller = $controller = new Controller($controllerId, Yii::$app);
        $controller->actionParams = $params;
        $controller->action = new Action($actionID, $controller);

        if ($moduleID !== null) {
            $controller->module = new Module($moduleID);
        }
    }

    /**
     * Removes controller
     */
    protected function removeMockedAction()
    {
        Yii::$app->controller = null;
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
        Yii::$container = new Container();
    }

    /**
     * Asserting two strings equality ignoring line endings
     *
     * @param string $expected
     * @param string $actual
     */
    public function assertEqualsWithoutLE($expected, $actual)
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Asserting two strings equality ignoring line endings
     *
     * @param string $needle
     * @param string $haystack
     */
    public function assertContainsWithoutLE($needle, $haystack)
    {
        $needle = str_replace("\r\n", "\n", $needle);
        $haystack = str_replace("\r\n", "\n", $haystack);

        $this->assertContains($needle, $haystack);
    }
}
