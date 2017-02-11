<?php

namespace App\RestBundle\Controller;

use App\CoreBundle\Entity\Brand;
use App\CoreBundle\Entity\Phone;
use App\CoreBundle\Form\PhoneType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PhoneByBrandController extends Controller
{
    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Returns all existing phones for selected brand",
     *  output="App\CoreBundle\Entity\Phone"
     * )
     *
     * @param integer $brandId
     * @return array[Phone]
     */
    public function getPhonesAction($brandId)
    {
        $repo = $this->container->get('app.dataManager')->getRepo(Brand::class);

        $brand = $repo->findWithPhones($brandId);

        if (!$brand) {
            throw $this->createNotFoundException();
        }

        return $brand->getPhones()->toArray();
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Create a new phone belonging to the selected brand",
     *  input="App\CoreBundle\Form\PhoneType",
     *  output="App\CoreBundle\Entity\Phone"
     * )
     *
     * @param integer $brandId
     * @return Phone
     */
    public function postPhoneAction(Request $request, $brandId)
    {
        $brand = $this->stdGetSingleAction(Brand::class, $brandId);

        $repo = $this->container->get('app.dataManager')->getRepo(Phone::class);

        $entity = new Phone();
        $entity->setBrand($brand);

        $form = $this->prepareFormAndSubmit($request, PhoneType::class, $entity);

        if ($form->isValid()) {
            $repo->persist($entity);
        } else {
            throw new BadRequestHttpException('Invalid values for entity');
        }

        return $entity;
    }
}
