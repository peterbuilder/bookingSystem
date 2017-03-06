<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/notAcceptedTask")
     * @Template()
     */
    public function getNotAcceptedTasksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findBy(array('isAccepted' => 0));

        return ['tasks' => $tasks];
    }

    /**
     * @Route("/acceptTask/{id}")
     */
    public function acceptTaskAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->findOneBy(array('isAccepted' => 0, 'id'=>$id));
        $task->setIsAccepted(1);
        $em->persist($task);
        $em->flush();

        return new Response();
    }


}