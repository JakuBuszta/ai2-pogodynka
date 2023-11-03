<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('format')] string $format,
        #[MapQueryParameter('twig')] bool $twig = false,
        WeatherUtil $util
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if ($format == 'json') {
            if ($twig){
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            } else {
                return $this->json([
                    'city' => $city,
                    'country' => $country,
                    'measurements' => array_map(fn(Measurement $m) => [
                        'date' => $m->getDate()->format('Y-m-d'),
                        'celsius' => $m->getCelsius(),
                        'fahrenheit' => $m->getFahrehneit(),
                    ], $measurements),
                ]);
            }

        } elseif ($format == 'csv') {
            if($twig){
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            } else {
                $csv = "city,country,date,celsius,fahrenheit\n";
                $csv .= implode(
                    "\n",
                    array_map(fn(Measurement $m) => sprintf(
                        '%s,%s,%s,%s',
                        $city,
                        $country,
                        $m->getDate()->format('Y-m-d'),
                        $m->getCelsius(),
                        $m->getFahrehneit(),
                    ), $measurements)
                );

                return new Response($csv, 200, [
//                'Content-Type' => 'text/csv',
                ]);
            }
        }
    }
}
