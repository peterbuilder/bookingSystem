<?php
/**
 * Created by PhpStorm.
 * User: piotr
 * Date: 23.02.17
 * Time: 14:20
 */

namespace AppBundle\Controller;



use AppBundle\AppBundle;
use AppBundle\Entity\Task;
use AppBundle\Repository\taskRepository;
use DateInterval;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $endTime = $this->addServiceTime($startTime, $serviceType);

        if(!$this->isDateBusy($date, $startTime)) {
            $this->setTask($serviceType, $startTime, $endTime);
        }

        return [];
    }

    /**
     * @Route ("/tasks/{year}/{month}/{day}")
     * @Template()
     */
    public function getTasksAction($year, $month, $day)
    {
        $date = $year.'-'.$month.'-'.$day;
        $date = $this->checkDateFormat($date);
        $date = $date.'%';
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findTaskByDate($date);


        return ['tasks' => $tasks];
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

    private function isDateBusy($date, $startCurrentTask)
    {
        $a=0;
        $date = $date . '%';
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findTaskByDate($date);

        foreach($tasks as $key => $task) {
            $start = $task->getStart();
            $end = $task->getEnd();

            if(($startCurrentTask >= $start) && ($startCurrentTask < $end)) {
                return true;
            }

        }
        return false;
    }

    private function setTask($serviceType, $startTime, $endTime)
    {
        $task = new Task();
        $task->setUser($this->getUser());
        $task->setType($serviceType);
        $task->setStart($startTime);
        $task->setEnd($endTime);

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();
    }

    private function addServiceTime($startTime, $serviceType)
    {
        $time = new DateTime($startTime);

        switch ($serviceType) {
            case 'manHaircut':
                $time->add(new DateInterval('PT15M'));
                break;

            case 'womanHaircut':
                $time->add(new DateInterval('PT30M'));
                break;

            case 'permanent':
                $time->add(new DateInterval('PT1H30M'));
                break;
        }

        $endTime = $time->format('Y-m-d H:i:s');

        return $endTime;
    }


}