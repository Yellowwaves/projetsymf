<?php
/**
 * Description du fichier : Ce fichier contient les fonctions de gestion de l'API
 *
 * @category   Fonctions controller API
 * @package    App
 * @subpackage Controller
 * @author     Elouan Teissere
 * @version    1.0 - 08/05/2023
 *
 */
namespace App\Controller;
use App\Entity\Seismes;
use App\Repository\SeismesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    private $seismesRepository;

    // On injecte le repository des séismes dans le constructeur
    public function __construct(SeismesRepository $seismesRepository)
    {
        $this->seismesRepository = $seismesRepository;
    }

    #[Route('/', name: 'seismes_api_index', methods: ['GET'])]
    //On récupère tous les séismes et on les affiche en JSON
    public function index(EntityManagerInterface $entityManager): Response
    {
        $seismes = $entityManager->getRepository(Seismes::class)->findAll();

        // création de la réponse JSON avec les en-têtes ajoutés
        $response = $this->json(['seismes' => $seismes]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, DELETE, PUT');
        
        return $response;
    }

    //On récupère un séisme et on l'affiche en JSON
    #[Route('/{id}', name: 'seismes_api_show', methods: ['GET'])]
    public function show($id): Response
    {
        $seismes = $this->seismesRepository->findByLimit($id);

        // création de la réponse JSON avec les en-têtes ajoutés
        $response = $this->json(['seismes' => $seismes]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, DELETE, PUT');
        
        return $response;
    }

    /*
    #[Route('/new', name: 'seismes_api_new', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $seisme = new Seismes();
        $seisme->setMagnitude($data['magnitude']);
        $seisme->setPays($data['pays']);
        // etc. - ajoutez ici les setters pour les autres propriétés

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($seisme);
        $entityManager->flush();

        return $this->json($seisme);
    }
    
    #[Route('/{id}/edit', name: 'seismes_api_edit', methods: ['PUT'])]
    public function edit(Request $request, Seismes $seisme): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $seisme->setMagnitude($data['magnitude']);
        $seisme->setPays($data['pays']);
        // etc. - ajoutez ici les setters pour les autres propriétés

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($seisme);
        $entityManager->flush();

        return $this->json($seisme);
    }

    #[Route('/{id}', name: 'seismes_api_delete', methods: ['DELETE'])]
    public function delete(Seismes $seisme): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($seisme);
        $entityManager->flush();

        return $this->json(['message' => 'Le séisme a bien été supprimé.']);
    }*/
}
