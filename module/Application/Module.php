<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;

class Module
{
	public function init(ModuleManager $moduleManager)
	{
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
			$controller = $e->getTarget();
			if (!$controller->zfcUserAuthentication()->hasIdentity()) {
				return $controller->redirect()->toRoute('zfcuser/login');
			}
		}, 100);
	}
	
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	// public function mvcPreDispatch($event)
	// {
		// $di = $event->getTarget()->getServiceManager();
		// $auth = $di->get('User\Event\Authentication');
		// return $auth->preDispatch($event);
	// }
}
