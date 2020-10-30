<?php 
namespace Student\Model;

use RuntimeException;

use Laminas\Hydrator\HydratorInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;

use Student\Model\Entity;

class Student{

	private $hydrator;
	private $entity;
    private $db;
    private $entityName = 'student';

    public function __construct(AdapterInterface $db,
        HydratorInterface $hydrator,
        Entity $postPrototype)
    {
        $this->db = $db;
        $this->hydrator      = $hydrator;
        $this->entity = $postPrototype;
    }

    public function get($id)
    {
        $resultSet = new ResultSet();
        $sql       = new Sql($this->db);
        $select    = $sql->select($this->entityName);
        $select->where(['id = ?' => $id]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            throw new RuntimeException(sprintf(
                'Failed retrieving data with identifier "%s"; unknown database error.',
                $id
            ));
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->entity);
        $resultSet->initialize($result);
        $student = $resultSet->current();

        return $student;
    }

    public function getAll()
    {

        $resultSet = new ResultSet();
        $sql = new Sql($this->db);
        $select = $sql->select($this->entityName);
        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if($result instanceof ResultInterface && $result->isQueryResult()){
            $resultSet->initialize($result);
        }

        return $resultSet;
    }

	public function create($student)
	{   

        if(empty($student->getName()) || empty($student->getAge())){
            return false;
        }

		$insert = new Insert($this->entityName);
        $insert->values([
            'name' => $student->getName(),
            'address' => $student->getAddress(),
            'age' => $student->getAge()
        ]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

    }
    
    public function delete($id)
    {
        if (!$id) {
            throw new RuntimeException('Cannot update post; missing identifier');
        }

        $delete = new Delete($this->entityName);
        $delete->where(['id = ?' => $id]);

        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        if (! $result instanceof ResultInterface) {
            return false;
        }

        return true;
    }

    public function update($student){

        if($id = $student->getId()){
            $update = new Update($this->entityName);
            $update->set([
                'name' => $student->getName(),
                'address' => $student->getAddress(),
                'age' => $student->getAge()
            ]);
            $update->where(['id = ?' => $id]);

            $sql = new Sql($this->db);
            $statement = $sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
    }
}