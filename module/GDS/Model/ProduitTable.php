<?php
namespace GDS\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ProduitTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
		$select = new Select($this->tableGateway->getTable());
		$select->order('libelle ASC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
	
	public function fetchAllForSelect()
	{
		$resultSet = $this->fetchAll();
		$produits = array();
		foreach($resultSet as $result) {
			$produits[$result->id] = $result->libelle;
		}
		return $produits;
	}

    public function getProduit($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProduit(Produit $produit)
    {
        $data = array(
            'id' => $produit->id,
            'libelle'  => $produit->libelle,
        );

        $id = (int)$produit->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProduit($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteProduit($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}