<?php namespace Wasp\Controller;

use Wasp\Controller\BaseController;

/**
 * A standard rest controller
 *
 * @package Wasp
 * @subpackage Controller
 * @author Dan Cox
 */
class RestController extends BaseController
{
	/**
	 * An instance of the entity class
	 *
	 * @var \Wasp\Entity\Entity
	 */
	protected $eObject;

	/**
	 * Create an instance of the entity
	 *
	 * @return \Wasp\Entity\Entity
	 * @author Dan Cox
	 */
	public function entityInstance()
	{
		$reflect = new \ReflectionClass($this->entity);

		return $this->eObject = $reflect->newInstance();	
	}

	/**
	 * Show a single record of the entity
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @method GET
	 * @author Dan Cox
	 */
	public function show($id)
	{
		try {
			
			$record = $this->database
						   ->setEntity($this->entity)
						   ->find($id);

			return $this->response->json($record->json(), 200);

		} catch (\Exception $e) {
			
			return $this->response->json(['status' => 'Invalid Identifier'], 404);
		}
	}

	/**
	 * Create a new instance of the entity
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @method POST
	 * @author Dan Cox
	 */
	public function create()
	{
		$data = $this->request->getInput();

		$entity = $this->entityInstance();

		try {
			
			$entity->updateFromArray($data);

			return $this->response->json($entity->json(), 200);
				
		} catch (\Exception $e) {

			return $this->response->json(['status' => 'fail', 'error' => $e->getMessage()], 400);
		}
	}

	/**
	 * Updates entity
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @method PATCH
	 * @author Dan Cox
	 */
	public function update($id)
	{

	}

	/**
	 * Delete the entity record
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @method GET
	 * @author Dan Cox
	 */
	public function delete($id)
	{
		try {

			$record = $this->database
						   ->setEntity($this->entity)
						   ->find($id)
						   ->delete();

			return $this->response->json(['status' => 'success'], 200);

		} catch (\Exception $e) {

			return $this->response->json(['status' => 'Invalid Identifier'], 404);
		}
	}

} // END class RestController extends BaseController

