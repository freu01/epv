<?php
namespace GDS\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use GDS\Model\Stock;          
use GDS\Form\StockForm;       

class StockController extends AbstractActionController
{
	protected $stockTable;
	
    public function indexAction()
    {
		$idEntrepot = (int) $this->params()->fromRoute('id', 0);
        if (!$idEntrepot) {
            return $this->redirect()->toRoute('entrepot', array(
                'action' => 'index'
            ));
        }
		return new ViewModel(array(
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
}