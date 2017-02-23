<?php
/**
 * Created by PhpStorm.
 * User: piotr
 * Date: 21.02.17
 * Time: 13:11
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class CalendarController extends Controller
{
    /**
     * @Route("/show/{day}/{month}/{year}")
     */
    public function showDayAction($day, $month, $year)
    {
        return new Response("Dzień: " . $day . " Miesiąc: " . $month . " Rok: " . $year);
    }

    //Count weeks in month
    private function numOfWeeks($month, $year)
    {
        $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $numberOfWeeks = 0;
        $day = 1;

        for($day; $day <= $numberOfDays; $day++) {
            $date = gregoriantojd($month, $day, $year);
            if(jddayofweek($date, 0) == 0) {
                $numberOfWeeks++;
            }
        }

        //If the last day of week isn't sunday: $numberOfWeeks++
        if(jddayofweek($date, 0) != 0) {
            $numberOfWeeks++;
        }

        return $numberOfWeeks;
    }


    /**
     * @Route("/ajax/calendar/{year}/{month}", name="ajax_calendar")
     */
    public function generateCalendarToAjaxAction($year, $month)
    {
        //Number of days in month
        $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        //Name of first day in week. If sunday: $dayOfWeek = 7
        $date = gregoriantojd($month, 1, $year);
        if(jddayofweek($date, 0) != 0) {
            $dayOfWeek = jddayofweek($date, 0);
        }
        else {
            $dayOfWeek = 7;
        }

        //Number of weeks in month
        $numberOfWeeks = $this->numOfWeeks($month, $year);

        $calendar = $this->renderView(
            'test.html.twig', [
                "numberOfDays" => $numberOfDays,
                "dayOfWeek" => $dayOfWeek,
                "month" => $month,
                "year" => $year,
                "numberOfWeeks" => $numberOfWeeks - 1
            ]
        );

        return new JsonResponse([
            "calendar" => $calendar
        ]);
    }
}
