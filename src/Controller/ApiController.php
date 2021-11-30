<?php

namespace App\Controller;

use App\Model\AbstractManager;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use DateTime;



class ApiController extends AbstractController
{
    public function abc($letter)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/search.php?f='.$letter);
        $drinks=($response->toArray())["drinks"];
        return $this->twig->render('Home/abc.html.twig', ['drinks' => $drinks, 'letter' => $letter]);
    }

}