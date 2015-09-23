<?php

namespace Wasp\Controller;

use Wasp\Controller\BaseController;
use Wasp\Utils\EntityHelper;

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
     * An array of options for pagination
     *
     * @var Array
     */
    protected $paginationOptions;

    /**
     * An Array of clauses
     *
     * @var Array
     */
    protected $filter = array();

    /**
     * Sets the entity
     *
     * @param String $entity
     * @return RestController
     * @author Dan Cox
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        $this->DI->get('database')->setEntity($entity);

        return $this;
    }

    /**
     * Create an instance of the entity
     *
     * @return \Wasp\Entity\Entity
     * @author Dan Cox
     */
    public function entityInstance()
    {
        return new $this->entity;
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
            $record = $this->DI->get('database')->find($id);

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
        $formatted = array();

        foreach ($errors as $error) {
            $formatted[] = array(
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
     * @param \Wasp\Entity\Entity $record
     * @return Response
     * @author Dan Cox
     */
    public function updateAndValidate($record = null)
    {
        $data = $this->request->getInput();

        // Create the entity if it doesn't exist
        if (is_null($record)) {
            $record = $this->entityInstance();
        }

        $record = EntityHelper::updateFromArray($record, $data->all());

        $errors = $this->validator->validate($record);

        if ($errors->count() > 0) {
            return $this->response->json([
                'status' => 'validation errors',
                'errors' => $this->formatErrors($errors)], 400);
        }

        $this->database->save($record);

        return $this->response->json(['status' => 'success', 'data' => $record->toArray()], 200);
    }

    /**
     * Gets options from the input param bag
     *
     * @return void
     * @author Dan Cox
     */
    public function getQueryOptions()
    {
        $this->paginationOptions = ['pageSize' => 100, 'page' => 0];
        $input = $this->request->getInput()->all();


        foreach ($input as $key => $value) {
            if (array_key_exists($key, $this->paginationOptions)) {
                $this->paginationOptions[$key] = $value;
            } else {
                $this->filter[$key] = $value;
            }
        }
    }

    /**
     * Shows bulk records
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @method GET
     * @author Dan Cox
     */
    public function all()
    {
        $this->getQueryOptions();

        $paginator = $this->paginator->setEntity($this->entity);

        try {
            $records = $paginator->query($this->paginationOptions['pageSize'], $this->filter);

            return $this->response->json($records->toArray(), 200);

        } catch (\Exception $e) {
            return $this->response->json(['status' => 'error', 'error' => $e->getMessage()], 400);
        }
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

        if (is_null($record)) {
            return $this->response->json(['status' => 'Invalid identifier'], 404);
        }

        return $this->response->json($record->toArray(), 200);
    }

    /**
     * Create a new instance of the entity
     *
     * @return Response
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

        if (is_null($record)) {
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

        if (is_null($record)) {
            return $this->response->json(['status' => 'Invalid identifier'], 404);
        }

        try {
            $this->database->remove($record);

            return $this->response->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            return $this->response->json(['status' => 'error', 'error' => $e->getMessage()], 400);
        }
    }
} // END class RestController extends BaseController
