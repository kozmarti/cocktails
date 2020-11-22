<?php

namespace App\Controller;

use App\Model\AbstractManager;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use DateTime;

class IngredientController extends AbstractController
{
    public function ingredients()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/list.php?i=list');
        $ingredients = ($response->toArray())["drinks"];
        return $this->twig->render('Home/ingredients.html.twig', ['ingredients' => $ingredients]);
    }
}
