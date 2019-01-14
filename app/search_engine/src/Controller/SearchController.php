<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/home", name="search_engine_home", methods={"GET"})
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function search(Request $request)
    {
        $keyWord = $request->get('keyWord', 'nothing');

        return $this->json([
        	["name" => "Avionet", "type" => "air", "icon" => "http://lorempixel.com/100/50/transport/2"],
			["name" => "Car", "type" => "ground", "icon" => "http://lorempixel.com/100/50/transport/8"],
			["name" => "Motorbike", "type" => "ground", "icon" => "http://lorempixel.com/100/50/transport/10"],
			["name" => "Plane", "type" => "air", "icon" => "http://lorempixel.com/100/50/transport/1"],
			["name" => "Train", "type" => "ground", "icon" => "http://lorempixel.com/100/50/transport/6"]
        ]);
    }
}
