<?php
/**
 * Created by PhpStorm.
 * User: piotr
 * Date: 23.02.17
 * Time: 14:20
 */

namespace AppBundle\Controller;



use AppBundle\Entity\Task;
use DateInterval;
use DateTime;
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
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Access denied');

        $serviceType = $request->request->get('serviceType');
        $start = $request->request->get('startTime');
        $date = $request->request->get('date');
        $date = $this->checkDateFormat($date);
        $startTime = $date . ' ' . $start . ':00';
        $time = new DateTime($startTime);

        switch ($serviceType) {
            case 'manHaircut':
                    $time->add(new DateInterval('PT15M'));
                    $endTime = $time->format('Y-m-d H:i:s');
                break;

            case 'womanHaircut':
                    $time->add(new DateInterval('PT30M'));
                    $endTime = $time->format('Y-m-d H:i:s');
                break;

            case 'permanent':
                    $time->add(new DateInterval('PT1H30M'));
                    $endTime = $time->format('Y-m-d H:i:s');
                break;
        }

        $this->setTask($serviceType, $startTime, $endTime);
        return ['endTime' => $startTime,'date'=>$date];
    }

    //Change day and month format to 0x-0x
    private function checkDateFormat($date)
    {
        $dateArray = explode('-', $date);
        foreach ($dateArray as $key => $element) {
            if(strlen($element) == 1) {
                $element = '0' . $element;
                $dateArray[$key] = $element;
            }
        }
        $date = implode('-', $dateArray);

        return $date;
    }

    private function setTask($serviceType, $startTime, $endTime)
    {
        $task = new Task();
        $task->setUser($this->getUser());
        $task->setUserId($this->getUser()->getId());
        $task->setType($serviceType);
        $task->setStart($startTime);
        $task->setEnd($endTime);

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();
    }
}