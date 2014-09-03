<?php

## Setting initialization
Setting::init(__FILE__);
$basePath = Setting::getBasePath();
$modules = Setting::getModules();

return array(
    'basePath' => $basePath,
    'name' => Setting::get('app.name'),
    // preloading 'log' component
    'preload' => array('log', 'EJSUrlManager'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.forms.*',
        'application.components.*',
        'application.components.ui.*',
        'application.components.ui.FormFields.*',
        'application.components.utility.*',
        'application.components.models.*',
        'application.components.codegen.*',
        'application.components.repo.*',
        'application.behaviors.*',
        'ext.YiiJasper.*',
    ),
    'sourceLanguage' => 'en_us',
    'language' => 'id',
    'modulePath' => Setting::getModulePath(),
    'modules' => array_merge($modules, array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123',
            'ipFilters' => array('127.0.0.1', '::1'),
        ))
    ),
    'aliases' => array(
        //Path to your Composer vendor dir plus vendor/bluecloudy path
        'YiiDoctrine' => realpath(__DIR__ . '/../vendor/bluecloudy/yiidoctrine2/bluecloudy/yiidoctrine2'),
    ),
    // application components
    'components' => array(
        'EJSUrlManager' => array(
            'class' => 'ext.JSUrlManager.EJSUrlManager'
        ),
        'curl' => array(
            'class' => 'ext.curl.Curl',
            'options' => array(CURLOPT_HEADER => true),
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ),
        'db' => Setting::getDB(),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
        'widgetFactory' => array(),
        'cache' => array(
            'class' => 'system.caching.CFileCache'
        ),
        'clientScript' => array(
            'packages' => array(
                'jquery' => array(
                    'basePath' => "application.static.js.lib",
                    'js' => array('jquery.js'),
                    'coreScriptPosition' => CClientScript::POS_HEAD
                )
            )
        )
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ),
);