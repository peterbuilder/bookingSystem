<?php
/**
 * Created by PhpStorm.
 * User: piotr
 * Date: 23.02.17
 * Time: 14:20
 */

namespace AppBundle\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskController extends Controller
{
    /**
     * @Route("/addTask")
     * @Method("POST")
     * @Template("AppBundle:Task:addTask.html.twig")
     */
    public function addTaskAction(Request $request)
    {
        $serviceType = $request->request->get('serviceType');


        return ['service' => $serviceType];
    }
}