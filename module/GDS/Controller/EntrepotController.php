<?php
namespace GDS\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use GDS\Model\Entrepot;          // <-- Add this import
use GDS\Form\EntrepotForm;       // <-- Add this import

class EntrepotController extends AbstractActionController
{
	protected $entrepotTable;
	
    public function indexAction()
    {
		return new ViewModel(array(
            'entrepots' => $this->getEntrepotTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
		$form = new EntrepotForm();
        $form->get('submit')->setValue('Ajouter');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $entrepot = new Entrepot();
            $form->setInputFilter($entrepot->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $entrepot->exchangeArray($form->getData());
                $this->getEntrepotTable()->saveEntrepot($entrepot);

                // Redirect to list of albums
                return $this->redirect()->toRoute('entrepot');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('entrepot', array(
                'action' => 'add'
            ));
        }
        $entrepot = $this->getEntrepotTable()->getEntrepot($id);

        $form  = new EntrepotForm();
        $form->bind($entrepot);
        $form->get('submit')->setAttribute('value', 'Modifier');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($entrepot->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntrepotTable()->saveEntrepot($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('entrepot');
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
            return $this->redirect()->toRoute('entrepot');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');

            if ($del == 'Oui') {
                $id = (int) $request->getPost('id');
                $this->getEntrepotTable()->deleteEntrepot($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('entrepot');
        }

        return array(
            'id'    	=> $id,
            'entrepot' 	=> $this->getEntrepotTable()->getEntrepot($id)
        );
    }
	
	public function getEntrepotTable()
    {
        if (!$this->entrepotTable) {
            $sm = $this->getServiceLocator();
            $this->entrepotTable = $sm->get('GDS\Model\EntrepotTable');
        }
        return $this->entrepotTable;
    }
}