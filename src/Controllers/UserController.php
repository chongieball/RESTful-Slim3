<?php 

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController extends BaseController
{
	public function index(Request $request, Response $response)
	{
		$user = new \App\Models\Users($this->db);
		$users = $user->getAll();

		$data['status'] = 200;
		$data['message'] = 'Data Available';
		$data['data'] = $users;

		return $response->withHeader('Content-type', 'application/json')->withJson($data, $data['status']);
	}

	public function register(Request $request, Response $response)
	{
		$rules = [
			'required'	=> [
				['username'],
				['email'],
				['password'],
			],
			'alphaNum'	=> [
				['username'],
			],
			'email'	=> [
				['email'],
			],
			'lengthMin'	=> [
				['username', 6],
				['password', 6],
			],
		];

		$this->validator->rules($rules);

		$this->validator->labels([
			'username'			=> 'Username',
			'email'				=> 'Email',
			'password'			=> 'Password',
		]);

		if ($this->validator->validate()) {
			$user = new \App\Models\Users($this->db);
			$register = $user->register($request->getParsedBody());

			$findUserAfterRegister = $user->findNotDelete('id', $register);

			$data['status'] = 201;
			$data['message'] = 'Register Success';
			$data['data'] = $findUserAfterRegister;
		} else {
			$data['status'] = 400;
			$data['message'] = 'Error';
			$data['data'] = $this->validator->errors();
		}

		return $response->withHeader('Content-type', 'application/json')->withJson($data, $data['status']);
	}

	public function update(Request $request, Response $response, $args)
	{
		$user = new \App\Models\Users($this->db);
		$findUser = $user->find('id', $args['id']);

		if ($findUser) {
			$rules = [
				'required'	=> [
					['username'],
					['email'],
					['password'],
				],
				'alphaNum'	=> [
					['username'],
				],
				'email'	=> [
					['email'],
				],
				'lengthMin'	=> [
					['username', 6],
					['password', 6],
				],
			];

			$this->validator->rules($rules);

			$this->validator->labels([
				'username'			=> 'Username',
				'email'				=> 'Email',
				'password'			=> 'Password',
			]);

			if ($this->validator->validate()) {
				$user->updateProfile($request->getParsedBody(), $args['id']);

				$data['status'] = 201;
				$data['message'] = 'Success Update Data';
				$data['data'] = $findUser;
			} else {
				$data['status'] = 400;
				$data['message'] = 'Error';
				$data['data'] = $this->validator->errors();
			}

		} else {
			$data['status'] = 404;
			$data['message'] = 'Data Not Found';
		}

		return $response->withHeader('Content-type', 'application/json')->withJson($data, $data['status']);
	}

	public function softDelete(Request $request, Response $response, $args)
	{
		$user = new \App\Models\Users($this->db);

		$findUser = $user->findNotDelete('id', $args['id']);

		if ($findUser) {
			$user->softDelete($args['id']);

			$data['status'] = 200;
			$data['message'] = 'Delete Success';
		} else {
			$data['status'] = 404;
			$data['message'] = 'Data Not Found';
		}

		return $response->withHeader('Content-type', 'application/json')->withJson($data, $data['status']);
	}

	public function findUser(Request $request, Response $response, $args)
	{
		$users = new \App\Models\Users($this->db);
		$findUser = $users->findNotDelete('id', $args['id']);

		if ($findUser) {
			$data['status'] = 200;
			$data['message'] = 'Data Available';
			$data['data'] = $findUser;
		} else {
			$data['status'] = 404;
			$data['message'] = 'Data Not Found';
		}

		return $response->withHeader('Content-type', 'application/json')->withJson($data, $data['status']);
	}

	public function hardDelete(Request $request, Response $response, $args)
	{
		$user = new \App\Models\Users($this->db);

		$findUser = $user->find('id', $args['id']);

		if ($findUser) {
			$user->hardDelete($args['id']);

			$data['status'] = 200;
			$data['message'] = 'Data has been delete permanently';
		} else {
			$data['status'] = 404;
			$data['message'] = 'Data Not Found';
		}

		return $response->withHeader('Content-type', 'application/json')->withJson($data, $data['status']);
	}
}