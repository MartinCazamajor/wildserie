<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @return Response
     * @Route("/add", name="add")
     */
    public function add(): Response
    {
        return $this->render("category/add.html.twig");
    }

}