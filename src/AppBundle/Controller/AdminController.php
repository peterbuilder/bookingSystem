<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/notAcceptedTask")
     * @Template()
     */
    public function getNotAcceptedTasksAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access Denied');
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findBy(array('isAccepted' => 0));

        return ['tasks' => $tasks];
    }

    /**
     * @Route("/acceptTask/{id}")
     * @Template()
     */
    public function acceptTaskAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Access Denied');
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->findOneBy(array('isAccepted' => 0, 'id'=>$id));
        $task->setIsAccepted(1);
        $em->persist($task);
        $em->flush();

        return [];
    }


}