<?php
namespace GDS\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class EntrepotTable
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

    public function getEntrepot($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveEntrepot(Entrepot $entrepot)
    {
        $data = array(
            'id' => $entrepot->id,
            'nom'  => $entrepot->nom,
        );

        $id = (int)$entrepot->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getEntrepot($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteEntrepot($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}