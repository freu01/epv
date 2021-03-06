<?php
namespace GDS\Form;

use Zend\Form\Form;

class StockForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('stock');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
		$this->add(array(
			'type' => 'Zend\Form\Element\Select',
            'name' => 'idEntrepot',
            'options' => array(
				'label' => 'Entrepot',
				'empty_option' => 'Choisissez un produit',
            ),
        ));
        // $this->add(array(
            // 'name' => 'idProduit',
            // 'attributes' => array(
                // 'type'  => 'text',
            // ),
            // 'options' => array(
                // 'label' => 'Produit',
            // ),
        // ));
		$this->add(array(
			'type' => 'Zend\Form\Element\Select',
			'name' => 'idProduit',
			'options' => array(
				'label' => 'Produit',
				'empty_option' => 'Choisissez un produit',
			)
		));
		$this->add(array(
            'name' => 'quantite',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Quantité',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}