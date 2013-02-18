<?php
namespace GDS\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use GDS\Model\Stock;          
use GDS\Form\StockForm; 
use GDS\Model\Entrepot;          
use GDS\Form\EntrepotForm;       

class StockController extends AbstractActionController
{
	protected $stockTable;
	protected $entrepotTable;
	
    public function indexAction()
    {
		$idEntrepot = (int) $this->params()->fromRoute('id', 0);
        if (!$idEntrepot) {
            return $this->redirect()->toRoute('entrepot', array(
                'action' => 'index'
            ));
        }
		return new ViewModel(array(
			'entrepot' => $this->getEntrepotTable()->getEntrepot($idEntrepot),
            'stocks' => $this->getStockTable()->getStock($idEntrepot),
        ));
    }
	
	public function getStockTable()
	{
		if (!$this->stockTable) {
            $sm = $this->getServiceLocator();
            $this->stockTable = $sm->get('GDS\Model\stockTable');
        }
        return $this->stockTable;
	}
	
	public function getEntrepotTable()
	{
		if (!$this->entrepotTable) {
			$sm = $this->getServiceLocator();
            $this->entrepotTable = $sm->get('GDS\Model\entrepotTable');
		}
		return $this->entrepotTable;
	}
}