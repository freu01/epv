<?php
namespace GDS\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use GDS\Model\Produit;          // <-- Add this import
use GDS\Form\ProduitForm;       // <-- Add this import

class ProduitController extends AbstractActionController
{
	protected $produitTable;
	
    public function indexAction()
    {
		return new ViewModel(array(
            'produits' => $this->getProduitTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
		$form = new ProduitForm();
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $produit = new Produit();
            $form->setInputFilter($produit->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $produit->exchangeArray($form->getData());
                $this->getProduitTable()->saveProduit($produit);

                // Redirect to list of albums
                return $this->redirect()->toRoute('produit');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('produit', array(
                'action' => 'add'
            ));
        }
        $produit = $this->getProduitTable()->getProduit($id);

        $form  = new ProduitForm();
        $form->bind($produit);
        $form->get('submit')->setAttribute('value', 'Modifier');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($produit->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getProduitTable()->saveProduit($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('produit');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('produit');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $id = (int) $request->getPost('id');
                $this->getProduitTable()->deleteProduit($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('produit');
        }

        return array(
            'id'    	=> $id,
            'produit' 	=> $this->getProduitTable()->getProduit($id)
        );
    }
	
	public function getProduitTable()
    {
        if (!$this->produitTable) {
            $sm = $this->getServiceLocator();
            $this->produitTable = $sm->get('GDS\Model\ProduitTable');
        }
        return $this->produitTable;
    }
}