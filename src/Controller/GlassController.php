<?php


namespace App\Controller;


use Symfony\Component\HttpClient\HttpClient;

class GlassController extends AbstractController
{
    public function glasses()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/list.php?g=list');
        $glasses = ($response->toArray())["drinks"];
        return $this->twig->render('Home/glasses.html.twig', ['glasses' => $glasses]);
    }


}