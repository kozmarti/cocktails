<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ItemManager;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class ItemController
 *
 */
class CocktailController extends AbstractController
{

    public function cocktail($cocktailName)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/search.php?s='.$cocktailName);
        $cocktail = ($response->toArray())["drinks"][0];
        $ingredients = [];
        $measures = [];
        foreach (range(0, 15) as $i) {
            if (!empty($cocktail['strIngredient' . $i])) {
                $ingredients[$i] =  $cocktail['strMeasure' .  $i] . ' ' . $cocktail['strIngredient' . $i];
            }
        }
        $color = ["blue", "orange", "green", "yellow", "pink"][rand(0,4)];



        return $this->twig->render('Item/show_cocktail.html.twig', ['cocktail' => $cocktail, 'ingredients' => $ingredients, "color" => $color]);
    }

    public function result($word)
    {
        $text='result for "' . urldecode($word).'"';
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/filter.php?i='.$word);
        if (!empty($response->getContent())) {
            $results=($response->toArray())["drinks"];
        } else {
            $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/filter.php?g='.$word);
            if (!empty($response->getContent())) {
                $results=($response->toArray())["drinks"];
            }else{
                $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/search.php?s='.$word);
                if (!empty($response->getContent())) {
                    $results=($response->toArray())["drinks"];
                }else{
                $results=[];
                $text='No result for "' . urldecode($word).'"';
                }
            }
        }
       

        return $this->twig->render('Item/result.html.twig', ['results' => $results, 'text' => $text]);
    }

    public function resultby()
    {
        if ($_POST['word']){
            $word = $_POST['word'];
            $text='result for "' . urldecode($word).'"';
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/filter.php?i='.$word);
            if (!empty($response->getContent())) {
                $results=($response->toArray())["drinks"];
            } else {
                $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/filter.php?g='.$word);
                if (!empty($response->getContent())) {
                    $results=($response->toArray())["drinks"];
                }else{
                    $response = $client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/search.php?s='.$word);
                    if (!empty($response->getContent())) {
                        $results=($response->toArray())["drinks"];
                    }else{
                    $results=[];
                    $text='No result for "' . urldecode($word).'"';
                    }
                }
            }
        }

        return $this->twig->render('Item/result.html.twig', ['results' => $results, 'text' => $text]);
    }



    /**
     * Display item listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function search()
    {


        return $this->twig->render('Item/search.html.twig');
    }


    /**
     * Display item informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);

        return $this->twig->render('Item/show.html.twig', ['item' => $item]);
    }


    /**
     * Display item edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item['title'] = $_POST['title'];
            $itemManager->update($item);
        }

        return $this->twig->render('Item/edit.html.twig', ['item' => $item]);
    }


    /**
     * Display item creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemManager = new ItemManager();
            $item = [
                'title' => $_POST['title'],
            ];
            $id = $itemManager->insert($item);
            header('Location:/item/show/' . $id);
        }

        return $this->twig->render('Item/add.html.twig');
    }


    /**
     * Handle item deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $itemManager = new ItemManager();
        $itemManager->delete($id);
        header('Location:/item/index');
    }
}
