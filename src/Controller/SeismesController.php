<?php
/**
 * Description du fichier : Ce fichier contient les fonctions de gestion du backoffice
 *
 * @category   Fonctions controller Backoffice
 * @package    App
 * @subpackage Controller
 * @author     Elouan Teissere
 * @version    1.0 - 08/05/2023
 *
 */

namespace App\Controller;

use App\Entity\Seismes;
use App\Repository\SeismesRepository;
use App\Form\SeismesType;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seismes')]
class SeismesController extends AbstractController
{

    private $seismesRepository;

    // On injecte le repository des séismes dans le constructeur
    public function __construct(SeismesRepository $seismesRepository)
    {
        $this->seismesRepository = $seismesRepository;
    }

    // On récupère tous les séismes et on les affiche
    #[Route('/', name: 'app_seismes_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $seismes = $entityManager
            ->getRepository(Seismes::class)
            ->findAll();

        return $this->render('seismes/index.html.twig', [
            'seismes' => $seismes,
        ]);
    }

    // On envoie un formulaire et on récupère les données par pays et intensité max
    #[Route('/search', name: 'app_seismes_search', methods: ['GET', 'POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $maxIntensity = $form["maxIntensity"]->getData();
            $pays = $form["pays"]->getData();
            $seismes = $this->seismesRepository->findByPaysAndMagMax($pays, $maxIntensity);

        } else {
            $seismes = $entityManager
                ->getRepository(Seismes::class)
                ->findAll();
        }

        return $this->render('seismes/search.html.twig', [
            'form' => $form->createView(),
            'seismes' => $seismes,
        ]);
    }

    // On récupère les données de statistiques sur les séismes par pays
    #[Route('/stats', name: 'app_stats', methods: ['GET'])]
    public function stats(EntityManagerInterface $em)
    {
        // Récupérer les informations de statistiques sur les séismes par pays
        $statsByPays = $em->getRepository(Seismes::class)->createQueryBuilder('s')
            ->select('s.pays, MIN(s.mag) as min_mag, MAX(s.mag) as max_mag, AVG(s.mag) as avg_mag')
            ->groupBy('s.pays')
            ->getQuery()
            ->getResult();

        // Renvoyer les données à la vue Twig
        return $this->render('seismes/stats.html.twig', [
            'statsByPays' => $statsByPays,
        ]);
    }

// Fonctions de base non utilisées générées par Symfony NEW SHOW EDIT DELETE
    #[Route('/new', name: 'app_seismes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $seisme = new Seismes();
        $form = $this->createForm(SeismesType::class, $seisme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($seisme);
            $entityManager->flush();

            return $this->redirectToRoute('app_seismes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seismes/new.html.twig', [
            'seisme' => $seisme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seismes_show', methods: ['GET'])]
    public function show(Seismes $seisme): Response
    {
        return $this->render('seismes/show.html.twig', [
            'seisme' => $seisme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seismes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seismes $seisme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeismesType::class, $seisme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seismes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seismes/edit.html.twig', [
            'seisme' => $seisme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_seismes_delete', methods: ['POST'])]
    public function delete(Request $request, Seismes $seisme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $seisme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($seisme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_seismes_index', [], Response::HTTP_SEE_OTHER);
    }
}
