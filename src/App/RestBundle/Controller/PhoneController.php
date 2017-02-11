<?php

namespace App\RestBundle\Controller;

use App\CoreBundle\Entity\Phone;
use App\CoreBundle\Form\PhoneType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PhoneController extends Controller
{
    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a phone resource",
     *  output="App\CoreBundle\Entity\Phone"
     * )
     *
     * @param integer $id
     * @return Phone
     */
    public function getPhoneAction($id)
    {
        return $this->stdGetSingleAction(Phone::class, $id);
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Returns all existing phones",
     *  output="App\CoreBundle\Entity\Phone"
     * )
     *
     * @return array[Phone]
     */
    public function getPhonesAction()
    {
        return $this->stdGetAllAction(Phone::class);
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Modify an existing phone",
     *  input="App\CoreBundle\Form\PhoneType",
     *  output="App\CoreBundle\Entity\Phone"
     * )
     *
     * @param integer $id
     * @return Phone
     */
    public function putPhoneAction(Request $request, $id)
    {
        return $this->stdPutAction(Phone::class, $request, PhoneType::class, $id);
    }

    /**
     * @Rest\View()
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a phone resource",
     *  output="App\CoreBundle\Entity\Phone"
     * )
     *
     * @param integer $id
     * @return Phone
     */
    public function deletePhoneAction($id)
    {
        return $this->stdDeleteAction(Phone::class, $id);
    }
}
