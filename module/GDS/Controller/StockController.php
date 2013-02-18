<?php
namespace GDS\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use GDS\Model\Stock;          
use GDS\Form\StockForm; 
use GDS\Model\Entrepot;          
use GDS\Form\EntrepotForm; 
use GDS\Model\Produit;          
use GDS\Form\ProduitForm;       

class StockController extends AbstractActionController
{
	protected $stockTable;
	protected $entrepotTable;
	protected $produitTable;
	
    public function indexAction()
    {
		$idEntrepot = (int) $this->params()->fromRoute('id', 0);
        // if (!$idEntrepot) {
            // return $this->redirect()->toRoute('entrepot', array(
                // 'action' => 'index'
            // ));
        // }
		return new ViewModel(array(
			'entrepot' => $this->getEntrepotTable()->getEntrepot($idEntrepot),
            'stocks' => $this->getStockTable()->getStockFromEntrepot($idEntrepot),
        ));
    }
	
	public function addAction()
    {
		$idEntrepot = (int) $this->params()->fromRoute('id', 0);
        if (!$idEntrepot) {
            return $this->redirect()->toRoute('entrepot', array(
                'action' => 'index'
            ));
        }
		
		$form = new StockForm();
        $form->get('submit')->setValue('Ajouter');
		$form->get('idEntrepot')->setValue($idEntrepot);
		
		$produits = $this->getProduitTable()->fetchAllForSelect();
		$form->get('idProduit')->setValueOptions($produits);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
            $stock = new Stock();
            $form->setInputFilter($stock->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $stock->exchangeArray($form->getData());
                $this->getStockTable()->saveStock($stock);

                return $this->redirect()->toRoute('stock', array(
					'action' => 'index',
					'id' => $idEntrepot));
            }
        }
        return array('form' => $form,
					 'entrepot' => $this->getEntrepotTable()->getEntrepot($idEntrepot));
    }
	
	public function debiterAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new StockForm();
		$form->get('id')->setValue($id);
		$form->get('submit')->setValue('Débiter');
		return array('form' => $form,
					 'stock' => $this->getStockTable()->getStock($id),
					 );
	}
	
	public function crediterAction()
	{
	
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
	
	public function getProduitTable()
	{
		if (!$this->produitTable) {
			$sm = $this->getServiceLocator();
            $this->produitTable = $sm->get('GDS\Model\produitTable');
		}
		return $this->produitTable;
	}
}