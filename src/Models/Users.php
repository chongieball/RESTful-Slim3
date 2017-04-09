<?php 

namespace App\Models;

class Users extends BaseModel
{
	protected $table = 'users';
	protected $column = ['id', 'username', 'email', 'password', 'deleted'];

	public function register(array $data)
	{
		$data = [
			'username'	=> $data['username'],
			'password'	=> password_hash($data['password'], PASSWORD_BCRYPT),
			'email'		=> $data['email'],
			];
		$this->create($data);

		return $this->db->lastInsertId();
	}

	public function updateProfile(array $data, $id)
	{
		$data = [
			'username'	=> $data['username'],
			'password'	=> password_hash($data['password'], PASSWORD_BCRYPT),
			'email'		=> $data['email'],
		];
		
		$this->update($data, 'id', $id);
	}
}