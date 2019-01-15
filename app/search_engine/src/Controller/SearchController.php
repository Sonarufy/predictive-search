<?php

namespace App\Controller;

use App\Service\PredictiveSearchService;
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
    public function search(Request $request, PredictiveSearchService $predictiveSearchService)
    {
        $keyWord = $request->get('keyWord', 'nothing');

        $result = $predictiveSearchService->search($keyWord);

        return $this->json($result);
    }
}
