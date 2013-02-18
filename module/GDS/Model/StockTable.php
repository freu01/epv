<?php
namespace GDS\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class StockTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
		$select = new Select($this->tableGateway->getTable());
		$select->order('nom ASC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getStock($id)
    {
        $id  = (int) $id;
		$select = new Select($this->tableGateway->getTable());
		$select->where('id = ' . $id);
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        return $row;
    }
	
	public function getStockFromEntrepot($idEntrepot)
	{
		$id = (int) $idEntrepot;
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select	->from($this->tableGateway->getTable())
				->join(array('p' => 'produit'), 'p.id = stock.idProduit', array('libelle'))
				->where('stock.idEntrepot = ' . $id)
				->order('p.libelle ASC');
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        return $results;
		
	}
	
	public function getStockFromProduitAndEntrepotIds($idEntrepot, $idProduit)
	{
		$idEntrepot = (int) $idEntrepot;
		$idProduit = (int) $idProduit;
		
		$select = new Select($this->tableGateway->getTable());
		$select->where('idEntrepot = ' . $idEntrepot)
			   ->where('idProduit = ' . $idProduit);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->current();
	}

    public function saveStock(Stock $stock)
    {
        $data = array(
            'id' => $stock->id,
            'idEntrepot' => $stock->idEntrepot,
            'idProduit' => $stock->idProduit,
            'quantite' => $stock->quantite,
        );

        $id = (int)$stock->id;
        if ($id == 0) {
			if($oldStock = $this->getStockFromProduitAndEntrepotIds($stock->idEntrepot, 
														 $stock->idProduit)) {
				$data["quantite"] += $oldStock->quantite;
				$this->tableGateway->update($data, array('id' => $oldStock->id));
			} else {
				$this->tableGateway->insert($data);
			}
        } else {
            if ($this->getStock($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteStock($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}