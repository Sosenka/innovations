<?php


namespace App\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     * @Route("/", name="get_nbp_api")
     */
    public function fetchNbpInformation()
    {
        $response = $this->client->request(
            'GET',
            'http://api.nbp.pl/api/exchangerates/tables/A?format=json'
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode === 200)
        {
            $content = $response->getContent();
            $content = $response->toArray();
            $response = $this->forward('App\Controller\CurrencyController::saveCurrency', [
                'content' => $content[0]['rates']
            ]);
        }else
        {
            return new JsonResponse(['status' => 'error', 'content' => 'failed to connect to api'], 404);
        }
        return new JsonResponse(['status' => 'success'], 200);
    }

}