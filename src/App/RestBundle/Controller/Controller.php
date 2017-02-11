<?php

namespace App\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class Controller: implementing common methods for App's REST controllers
 *
 * @package App\RestBundle\Controller
 */
abstract class Controller extends SymfonyController
{
    /**
     * @param string $className Entity's class name on which to perform the action
     * @param integer $id
     * @return \stdClass
     */
    protected function stdGetSingleAction($className, $id)
    {
        $repo = $this->container->get('app.dataManager')->getRepo($className);

        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }

    /**
     * @param string $className Entity's class name on which to perform the action
     * @return array
     */
    protected function stdGetAllAction($className)
    {
        $repo = $this->container->get('app.dataManager')->getRepo($className);

        $entities = $repo->findAll();

        return $entities->toArray();
    }

    /**
     * @param string $className Entity's class name on which to perform the action
     * @param Request $request
     * @param \Symfony\Component\Form\AbstractType $form
     * @return \stdClass
     */
    protected function stdPostAction($className, $request, $form)
    {
        $repo = $this->container->get('app.dataManager')->getRepo($className);

        $entity = new $className();

        $form = $this->prepareFormAndSubmit($request, $form, $entity);

        if ($form->isValid()) {
            $repo->persist($entity);
        } else {
            throw new BadRequestHttpException('Invalid values for entity');
        }

        return $entity;
    }

    /**
     * @param string $className Entity's class name on which to perform the action
     * @param Request $request
     * @param \Symfony\Component\Form\AbstractType $form
     * @param integer $id
     * @return \stdClass
     */
    protected function stdPutAction($className, $request, $form, $id)
    {
        $repo = $this->container->get('app.dataManager')->getRepo($className);

        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $form = $this->prepareFormAndSubmit($request, $form, $entity);

        if ($form->isValid()) {
            $repo->persist($entity);
        } else {
            throw new BadRequestHttpException('Invalid values for entity');
        }

        return $entity;
    }

    /**
     * @param string $className Entity's class name on which to perform the action
     * @param integer $id
     * @return \stdClass
     */
    protected function stdDeleteAction($className, $id)
    {
        $repo = $this->container->get('app.dataManager')->getRepo($className);

        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $repo->remove($entity);

        return $entity;
    }

    /**
     * Set the form's data based on the request and submit updates to the object
     *
     * @param Request $request
     * @param \Symfony\Component\Form\AbstractType $form
     * @param \stdClass $object
     * @return \Symfony\Component\Form\Form
     */
    protected function prepareFormAndSubmit($request, $form, $object)
    {
        if ($request->getContentType() == 'json') {
            $data = json_decode($request->getContent(), true);
            $formParams = array('csrf_protection' => false);
        } else {
            $data = $request->getContent();
            $formParams = array();
        }

        $form = $this->createForm($form, $object, $formParams);

        $form->submit($data);

        return $form;
    }
}
