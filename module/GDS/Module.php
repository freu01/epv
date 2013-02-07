<?php
namespace GDS;

use GDS\Model\Entrepot;
use GDS\Model\Produit;
use GDS\Model\EntrepotTable;
use GDS\Model\ProduitTable;
use GDS\Model\Stock;
use GDS\Model\StockTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
	
	public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'GDS\Model\EntrepotTable' =>  function($sm) {
                    $tableGateway = $sm->get('EntrepotTableGateway');
                    $table = new EntrepotTable($tableGateway);
                    return $table;
                },
                'EntrepotTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entrepot());
                    return new TableGateway('entrepot', $dbAdapter, null, $resultSetPrototype);
                },
				'GDS\Model\ProduitTable' =>  function($sm) {
                    $tableGateway = $sm->get('ProduitTableGateway');
                    $table = new ProduitTable($tableGateway);
                    return $table;
                },
                'ProduitTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Produit());
                    return new TableGateway('produit', $dbAdapter, null, $resultSetPrototype);
                },
				'GDS\Model\StockTable' =>  function($sm) {
                    $tableGateway = $sm->get('StockTableGateway');
                    $table = new StockTable($tableGateway);
                    return $table;
                },
                'StockTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Stock());
                    return new TableGateway('stock', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}