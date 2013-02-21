<?php
namespace GDS\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;

class Stock
{
    public  $id;
    public $idEntrepot;
	public $idProduit;
	public $quantite;
	protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     	  	= (isset($data['id'])) 			? $data['id'] 			: null;
        $this->idEntrepot 	= (isset($data['idEntrepot'])) 	? $data['idEntrepot'] 	: null;
        $this->idProduit    = (isset($data['idProduit'])) 	? $data['idProduit'] 	: null;
        $this->quantite   	= (isset($data['quantite'])) 	? $data['quantite'] 	: null;
    }
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }
	
	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
	
	public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
			
            $inputFilter->add($factory->createInput(array(
                'name'     => 'idEntrepot',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
			
            $inputFilter->add($factory->createInput(array(
                'name'     => 'idProduit',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
			
            $inputFilter->add($factory->createInput(array(
                'name'     => 'quantite',
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}