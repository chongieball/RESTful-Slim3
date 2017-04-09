<?php 

namespace App\Models;

abstract class BaseModel
{
	protected $table;
	protected $column;
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function getAll()
	{
		$qb = $this->db->createQueryBuilder();
        $qb->select($this->column)
           ->from($this->table);

        $result = $qb->execute();
        return $result->fetchAll();
	}

	public function create(array $data)
    {
        $column = [];
        $paramData = [];
        foreach ($data as $key => $value) {
            $column[$key] = ':'.$key;
            $paramData[$key] = $value;
        }
        $qb = $this->db->createQueryBuilder();
        $qb->insert($this->table)
           ->values($column)
           ->setParameters($paramData)
           // echo $qb->getSQL();
           ->execute();
    }

    //conditional edit
    public function update(array $data, $column, $value)
    {
        $columns = [];
        $paramData = [];

        $qb = $this->db->createQueryBuilder();
        $qb->update($this->table);
        foreach ($data as $key => $values) {
            $columns[$key] = ':'.$key;
            $paramData[$key] = $values;

            $qb->set($key, $columns[$key]);
        }
        $qb->where( $column.'='. $value)
           ->setParameters($paramData)
           ->execute();
    }

    //conditional find
    public function find($column, $value)
    {
        $param = ':'.$column;

        $qb = $this->db->createQueryBuilder();
        $qb->select($this->column)
           ->from($this->table)
           ->where($column . ' = '. $param)
           ->setParameter($param, $value);
           // echo $qb->getSQL();
           // die();
        $result = $qb->execute();

        return $result->fetch();
    }

    //conditional find where deleted = 0
    public function findNotDelete($column, $value)
    {
        $param = ':'.$column;

        $qb = $this->db->createQueryBuilder();
        $qb->select($this->column)
           ->from($this->table)
           ->where($column . ' = '. $param)
           ->andWhere('deleted = 0')
           ->setParameter($param, $value);
           // echo $qb->getSQL();
           // die();
        $result = $qb->execute();

        return $result->fetch();
    }

    public function softDelete($id)
    {
    	$param = ':id';

    	$qb = $this->db->createQueryBuilder();
    	$qb->update($this->table)
    	   ->set('deleted', 1)
    	   ->where('id = '. $param)
    	   ->setParameter($param, $id)
    	   ->execute();
    }

    public function hardDelete($id)
    {
        $param = ':id';

        $qb = $this->db->createQueryBuilder();
        $qb->delete($this->table)
           ->where('id = '. $param)
           ->setParameter($param, $id)
           ->execute();
    }
}