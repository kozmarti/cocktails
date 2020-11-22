<?php

namespace App\Controller;

use App\Model\AbstractManager;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use DateTime;



class ApiController extends AbstractController
{
    public function cocktails()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/search.php?f=c');
        $drinks=($response->toArray())["drinks"];

        foreach ($drinks as $drink){
            echo '<pre>';
            echo $drink["strDrink"];
        }

    }
    public function abc(){
        return $this->twig->render('Home/abc.html.twig');
    }

}