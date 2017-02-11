<?php

namespace App\RestBundle\Controller;

use App\CoreBundle\Entity\Brand;
use App\CoreBundle\Form\BrandType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
        return $this->stdGetSingleAction(Brand::class, $id);
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
        return $this->stdGetAllAction(Brand::class);
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
        return $this->stdPostAction(Brand::class, $request, BrandType::class);
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
     * @param integer $id
     * @return Brand
     */
    public function putBrandAction(Request $request, $id)
    {
        return $this->stdPutAction(Brand::class, $request, BrandType::class, $id);
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
        return $this->stdDeleteAction(Brand::class, $id);
    }
}
