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
	 * Finds an entity by its id
	 *
	 * @param String|Integer $id
	 * @return \Wasp\Entity\Entity|NULL
	 * @author Dan Cox
	 */
	public function findEntity($id)
	{
		try {
			
			$record = $this->database->setEntity($this->entity)->find($id);

			return $record;

		} catch (\Exception $e) {

			return null;
		}
	}

	/**
	 * Formats errors into a nicely structured array
	 *
	 * @param \Symfony\Component\Validator\ConstraintViolationList $errors
	 * @return Array
	 * @author Dan Cox
	 */
	public function formatErrors($errors)
	{
		$formatted = Array();

		foreach ($errors as $error)
		{
			$formatted[] = Array(
				'property' => $error->getPropertyPath(), 
				'message' => $error->getMessage(), 
				'code' => $error->getCode(), 
				'value' => $error->getInvalidValue()
			); 
		}

		return $formatted;
	}

	/**
	 * Updates and validates the entity from request data
	 *
	 * @return Response
	 * @author Dan Cox
	 */
	public function updateAndValidate($record = NULL)
	{
		$data = $this->request->getInput();

		// Create the entity if it doesn't exist
		if (is_null($record))
		{
			$record = $this->entityInstance();
		}

		$record->updateFromArray($data->all());

		$errors = $this->validator->validate($record);

		if ($errors->count() > 0)
		{
			return $this->response->json(['status' => 'validation errors', 'errors' => $this->formatErrors($errors)], 400);
		}

		$record->save();

		return $this->response->json(['status' => 'success', 'data' => $record->toArray()], 200);
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
		$record = $this->findEntity($id);
			
		if (is_null($record))
		{
			return $this->response->json(['status' => 'Invalid identifier'], 404);
		}

		return $this->response->json($record->toArray(), 200);
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
		return $this->updateAndValidate();	
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
		$data = $this->request->getInput();
		$record = $this->findEntity($id);

		if (is_null($record))
		{
			return $this->response->json(['status' => 'Invalid identifier'], 404);
		}

		return $this->updateAndValidate($record);	
	}

	/**
	 * Delete the entity record
	 *
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @method DELETE
	 * @author Dan Cox
	 */
	public function delete($id)
	{
		$record = $this->findEntity($id);
		
		if (is_null($record))
		{
			return $this->response->json(['status' => 'Invalid identifier'], 404);
		}

		try {
			
			$record->delete();
		
			return $this->response->json(['status' => 'success'], 200);		

		} catch(\Exception $e) {
			
			return $this->response->json(['status' => 'error', 'error' => $e->getMessage()], 400);
		}
	}

} // END class RestController extends BaseController

