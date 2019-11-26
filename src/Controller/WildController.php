<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                "No program found in program's table."
                );
        }
        $category = new Category();
        $form = $this->createForm(
            CategoryType::class,
            $category);

        return $this->render(
            'wild/index.html.twig', [
                'programs' => $programs,
                'form' => $form->createView(),
                ]);
    }

    /**
     * @param string $slug
     * @return Response
     * @Route("/show/{slug}",
     *      requirements={"slug"="^[a-z0-9-]+$"},
     *      defaults={"slug"= null},
     *      name="show"
     *     )
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException("No slug has been sent to find a program in program's table.");
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                "No program with " . $slug . "title found in program's table."
            );
        }

        return $this->render("wild/show.html.twig", [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @param string $categoryName
     * @return Response
     * @Route("/category/{categoryName}",
     *     requirements={"categoryName"="^[a-z0-9-]+$"},
     *      defaults={"categoryName"= null},
     *      name="show_category"
     *     )
     */
    public function showByCategory(?string $categoryName): Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException("No category name has been sent to find a program in program's table.");
        }
        $categoryName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($categoryName)), "-")
        );
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($categoryName);

            $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findByCategory($category, ['id' => 'DESC'], 3);

        return $this->render("wild/category.html.twig", [
            'programs' => $programs,
            'category' => $category,
        ]);
    }

    /**
     * @param string $programName
     * @Route("/program/{programName}",
     *     requirements={"programName"="^[a-z0-9-]+$"},
     *     defaults={"programName"=null},
     *     name="show_program")
     * @return Response
     */
    public function showByProgram(?string $programName): Response
    {
        if (!$programName) {
            throw $this
            ->createNotFoundException("No program name has been sent to find seasons in season's table.");
        }
        $programName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($programName)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title'=>$programName]);
        $seasons = $program->getSeasons();
        return $this->render("wild/program.html.twig", [
            "program" => $program,
            "seasons" => $seasons,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @Route("/season/{id}",
     *     requirements={"id"="^[0-9-]+$"},
     *     defaults={"id"=null},
     *     name="show_season")
     */
    public function showBySeason(?int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException("No season has been sent to find episodes in episode's table.");
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneById($id);
        $program = $season->getProgram();
        $episodes = $season->getEpisodes();
        return $this->render("wild/season.html.twig", [
            "season" => $season,
            "episodes" => $episodes,
            "program" => $program,
        ]);
    }

    /**
     * @param Episode $episode
     * @Route("/episode/{id}", name ="show_episode")
     * @return Response
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        return $this->render("wild/episode.html.twig", [
            "episode" => $episode,
            "season" => $season,
            "program" => $program,
        ]);
    }
}
