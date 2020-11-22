<?php

namespace App\Controller;

use App\Model\AbstractManager;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use DateTime;

class RandomController extends AbstractController
{
    public function randomCocktail()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/random.php');
        $random = ($response->toArray())["drinks"][0];
        $ingredients = [];
        $measures = [];
        foreach (range(0, 15) as $i) {
            if (!empty($random['strIngredient' . $i])) {
                $ingredients[$i] =  $random['strMeasure' .  $i] . ' ' . $random['strIngredient' . $i];
            }
        }



        return $this->twig->render('Home/random.html.twig', ['random' => $random, 'ingredients' => $ingredients]);
    }
}
