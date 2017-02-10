<?php

namespace App\RestBundle\Controller;

use App\CoreBundle\Entity\Brand;
use App\CoreBundle\Form\BrandType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BrandController extends Controller
{
    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a brand resource",
     *  output="App\CoreBundle\Entity\Brand"
     * )
     *
     * @param integer $id
     * @return Brand
     */
    public function getBrandAction($id)
    {
        $repo = $this->container->get('app.dataManager')->getRepo(Brand::class);

        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        return $entity;
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Returns all existing brands",
     *  output="App\CoreBundle\Entity\Brand"
     * )
     *
     * @return array[Brand]
     */
    public function getBrandsAction()
    {
        $repo = $this->container->get('app.dataManager')->getRepo(Brand::class);

        $entities = $repo->findAll();

        return $entities->toArray();
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Create a new brand",
     *  input="App\CoreBundle\Form\BrandType",
     *  output="App\CoreBundle\Entity\Brand"
     * )
     *
     * @return Brand
     */
    public function postBrandAction(Request $request)
    {
        $repo = $this->container->get('app.dataManager')->getRepo(Brand::class);

        $entity = new Brand();

        $form = $this->prepareFormAndSubmit($request, $entity);

        if ($form->isValid()) {
            $repo->persist($entity);
        } else {
            throw new BadRequestHttpException('Invalid values for entity');
        }

        return $entity;
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Modify an existing brand",
     *  input="App\CoreBundle\Form\BrandType",
     *  output="App\CoreBundle\Entity\Brand"
     * )
     *
     * @return Brand
     */
    public function putBrandAction(Request $request, $id)
    {
        $repo = $this->container->get('app.dataManager')->getRepo(Brand::class);

        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $form = $this->prepareFormAndSubmit($request, $entity);

        if ($form->isValid()) {
            $repo->persist($entity);
        } else {
            throw new BadRequestHttpException('Invalid values for entity');
        }

        return $entity;
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a brand resource",
     *  output="App\CoreBundle\Entity\Brand"
     * )
     *
     * @param integer $id
     * @return Brand
     */
    public function deleteBrandAction($id)
    {
        $repo = $this->container->get('app.dataManager')->getRepo(Brand::class);

        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        $repo->remove($entity);

        return $entity;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\Form\Form
     */
    protected function prepareFormAndSubmit($request, $object)
    {
        if ($request->getContentType() == 'json') {
            $data = json_decode($request->getContent(), true);
            $formParams = array('csrf_protection' => false);
        } else {
            $data = $request->getContent();
            $formParams = array();
        }

        $form = $this->createForm(new BrandType(), $object, $formParams);

        $form->submit($data);

        return $form;
    }
}
