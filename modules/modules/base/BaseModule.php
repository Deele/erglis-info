<?php

namespace app\modules\modules\base;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

abstract class BaseModule extends Module implements BootstrapInterface
{

    /**
     * Module identifier
     */
    const MODULE = '';

    /**
     * Console application ID
     * @var string
     */
    public $consoleAppId = 'console';

    /**
     * @var string|array|null
     */
    public $cacheConfig = null;

    /**
     * @var bool
     */
    public $enableCaching = true;

    /**
     * @var array
     */
    public $translations = [];

    /**
     * @var \yii\caching\Cache
     */
    protected $_cache;

    /**
     * Initializes config files
     */
    public function init()
    {
        parent::init();
        $configFileDirPath = __DIR__ . '/config';
        if (file_exists($configFileDirPath)) {
            $commonConfigFilePath = $configFileDirPath . '/common.php';
            if (file_exists($commonConfigFilePath)) {
                \Yii::configure($this, require $commonConfigFilePath);
            }
            if (Yii::$app->id == $this->consoleAppId) {
                $consoleAppConfigFilePath = $configFileDirPath . '/console.php';
                if (file_exists($consoleAppConfigFilePath)) {
                    \Yii::configure($this, require $consoleAppConfigFilePath);
                }
            } else {
                $webAppConfigFilePath = $configFileDirPath . '/web.php';
                if (file_exists($webAppConfigFilePath)) {
                    \Yii::configure($this, require $webAppConfigFilePath);
                }
            }
        }
    }

    /**
     * @return BaseModule|Module
     */
    public static function instance()
    {
        return \Yii::$app->getModule(static::MODULE);
    }

    /**
     * @return \yii\caching\Cache|\yii\caching\CacheInterface
     */
    public function getCache()
    {
        if (is_null($this->_cache)) {
            if ($this->enableCaching) {
                $cacheConfig = $this->cacheConfig;
                if (is_string($cacheConfig)) {
                    $this->_cache = Yii::$app->get($cacheConfig, false);
                    if (is_null($this->_cache)) {
                        Yii::error(
                            'Invalid cache component ID provided to ' . static::MODULE . ' module'
                        );
                    }
                } elseif (is_array($cacheConfig)) {
                    $this->_cache = Yii::createObject($cacheConfig);
                }
            }
        }
        if (is_null($this->_cache)) {
            $this->_cache = Yii::createObject([
                'class' => 'yii\caching\DummyCache'
            ]);
        }

        return $this->_cache;
    }

    public function bootstrap($app)
    {
        $migrationsDirectoryPath = $this->basePath . '/migrations';
        if (file_exists($migrationsDirectoryPath) && isset($app->controllerMap['migrate'])) {
            if (is_null($app->controllerMap['migrate']['migrationPath'])) {
                if (!isset($app->controllerMap['migrate']['migrationNamespaces'])) {
                    $app->controllerMap['migrate']['migrationNamespaces'] = [];
                }
                $app->controllerMap['migrate']['migrationNamespaces'][] = 'app\\modules\\' . static::MODULE . '\\migrations';
            } else {
                Yii::warning(
                    'Could not add ' . static::MODULE . ' module migration namespace because migrationPath is not empty'
                );
            }
        }

        if (!empty($this->translations)) {
            $app->i18n->translations['app.modules.' . static::MODULE . '.*'] = $this->translations;
        }
    }
}
