<?php
namespace GDS\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

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
		$select->join(array('e' => 'entrepot'), 'e.id = stock.idEntrepot')
			   ->where('stock.idEntrepot = ' . $id);
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        return $row;
    }

    public function saveStock(Stock $stock)
    {
        $data = array(
            'id' => $stock->id,
            'nom'  => $stock->nom,
        );

        $id = (int)$stock->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
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