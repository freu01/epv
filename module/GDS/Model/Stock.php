<?php
namespace GDS\Model;

class Stock
{
    public  $id;
    private $idEntrepot;
	private $idStock;
	private $quantite;

    public function exchangeArray($data)
    {
        $this->id     	  	= (isset($data['id'])) 			? $data['id'] 			: null;
        $this->idEntrepot 	= (isset($data['idEntrepot'])) 	? $data['idEntrepot'] 	: null;
        $this->idStock    	= (isset($data['idStock'])) 	? $data['idStock'] 		: null;
        $this->quantite   	= (isset($data['quantite'])) 	? $data['quantite'] 	: null;
    }
	
	// public function getArrayCopy()
    // {
        // return get_object_vars($this);
    // }
	
	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
}