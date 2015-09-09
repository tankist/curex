<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Acl\Adapter\Memory as MemoryAclAdapter;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\RoleInterface as AclRoleInterface;
use Phalcon\Acl\Resource as AclResource;
use Phalcon\Acl\ResourceInterface as AclResourceInterface;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Forms\Manager as FormsManager;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});

$di->setShared('dispatcher', function () use ($di) {
    /** @var EventsManager $evManager */
    $evManager = $di->getShared('eventsManager');
    $evManager->attach('dispatch', $di->get('exceptionPlugin'));
    $evManager->attach('dispatch', $di->get('authPlugin'));
    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($evManager);
    return $dispatcher;
});

$di->setShared('router', function () use ($config) {
    return require __DIR__ . '/routes.php';
});

$di->setShared('authPlugin', [
    'className' => '\Plugin\Auth',
    'arguments' => [
        [
            'type' => 'service',
            'name' => 'acl',
        ],
    ],
]);

$di->setShared('exceptionPlugin', '\Plugin\Exception');

$di->setShared('acl', function () use ($di, $config) {
    $acl = new MemoryAclAdapter();
    if (isset($config->acl)) {
        if (isset($config->acl->roles)) {
            foreach ($config->acl->roles as $roleId => $role) {
                if (is_string($role)) {
                    $role = new AclRole($roleId, $role);
                }
                if (!$role instanceof AclRoleInterface) {
                    throw new InvalidArgumentException('Wrong ACL role provided');
                }
                $acl->addRole($role);
            }
        }
        if (isset($config->acl->resources)) {
            foreach ($config->acl->resources as $resourceId => $resource) {
                if (is_string($resource)) {
                    $resource = new AclResource($resourceId, $resource);
                }
                if (!$resource instanceof AclResourceInterface) {
                    throw new InvalidArgumentException('Wrong ACL resource provided');
                }
                $acl->addResource($resource);
            }
        }
        if (isset($config->acl->rights)) {
            if (isset($config->acl->rights->allow)) {
                /** @var \Phalcon\Config $allowConfig */
                foreach ($config->acl->rights->allow as $allowConfig) {
                    call_user_func_array([$acl, 'allow'], $allowConfig->toArray());
                }
            }
            if (isset($config->acl->rights->deny)) {
                /** @var \Phalcon\Config $denyConfig */
                foreach ($config->acl->rights->deny as $denyConfig) {
                    call_user_func_array([$acl, 'deny'], $denyConfig->toArray());
                }
            }
        }
    }
    return $acl;
});

$di['assets'] = function () {
    $assets = require_once __DIR__ . '/assets.php';
    return $assets;
};

// Register the flash service with custom CSS classes
$di['flash'] = function () {
    $flash = new \Phalcon\Flash\Direct(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );
    return $flash;
};
$di['flashSession'] = function () {
    $flash = new \Phalcon\Flash\Session(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );
    return $flash;
};

$di['session'] = function () {
    $session = new Session();
    $session->start();
    return $session;
};

$di['forms'] = function () {
    $formsManager = new FormsManager();
    $forms = (array) @include __DIR__ . "/forms.php";
    foreach ($forms as $name => $form) {
        $formsManager->set($name, $form);
    }
    return $formsManager;
};

return $di;