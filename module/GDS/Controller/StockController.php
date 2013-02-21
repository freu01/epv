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
// var_dump($request->getPost());exit;
            if ($form->isValid()) {
                $stock->exchangeArray($form->getData());
				$currentStock = $this->getStockTable()->getStockFromProduit(
					$stock->idEntrepot, 
					$stock->idProduit
				);
				
				if($currentStock){
					$stock->quantite += $currentStock->quantite;
				}
                $this->getStockTable()->saveStock($stock);

                return $this->redirect()->toRoute('stock', array(
					'action' => 'index',
					'id' => $idEntrepot));
            }
        }
        return array('form' => $form,
					 'entrepot' => $this->getEntrepotTable()->getEntrepot($idEntrepot));
    }
	
	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('stock', array(
                'action' => 'index'
            ));
        }
        $stock = $this->getStockTable()->getStock($id);

        $form  = new StockForm();
        $form->bind($stock);
        $form->get('submit')->setAttribute('value', 'Modifier');
		$form->get('idProduit')->setValue($stock->idProduit);
		
		$entrepots = $this->getEntrepotTable()->fetchAllForSelect();
		$form->get('idEntrepot')->setValueOptions($entrepots);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($stock->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getStockTable()->saveStock($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('stock');
            }
        }

        return array(
            'id' => $id,
			'entrepot' => $this->getEntrepotTable()->getEntrepot($stock->idEntrepot),
            'form' => $form,
        );
	}
	
	public function debiterAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		$action = 'Débiter';
		
		$form = new StockTransactionForm();
        $form->get('submit')->setValue($action);
		$form->get('id')->setValue($id);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
            $stock = new Stock();
            $form->setInputFilter($stock->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $stock->exchangeArray($form->getData());
				$currentStock = $this->getStockTable()->getStockFromProduit(
					$stock->idEntrepot, 
					$stock->idProduit
				);
				
				if($currentStock){
					$stock->quantite += $currentStock->quantite;
				}
                $this->getStockTable()->saveStock($stock);

                return $this->redirect()->toRoute('stock', array(
					'action' => 'index',
					'id' => $idEntrepot));
            }
        }
		
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