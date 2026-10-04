<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Security\Voter\CandidatureVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Enum\StatusCandidature;

#[Route('/candidature')]
#[IsGranted('ROLE_USER')]
final class CandidatureController extends AbstractController
{
    #[Route(name: 'app_candidature_index', methods: ['GET'])]
    public function index(Request $request, CandidatureRepository $candidatureRepository): Response
    {
        $status = StatusCandidature::tryFrom($request->query->getString('status'));
        $recherche = trim($request->query->getString('q'));
        $tri = $request->query->getString('tri', 'desc');

        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatureRepository->findByFilters($this->getUser(), $status, $recherche, $tri),
            'statuts' => StatusCandidature::cases(),
            'statutActuel' => $status,
            'recherche' => $recherche,
            'tri' => $tri,
        ]);
    }

    #[Route('/new', name: 'app_candidature_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidature->setUser($this->getUser());
            $entityManager->persist($candidature);
            $entityManager->flush();

            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidature/new.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidature_show', methods: ['GET'])]
    #[IsGranted(CandidatureVoter::VIEW, subject: 'candidature')]
    public function show(Candidature $candidature): Response
    {
        return $this->render('candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidature_edit', methods: ['GET', 'POST'])]
    #[IsGranted(CandidatureVoter::EDIT, subject: 'candidature')]
    public function edit(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidature_delete', methods: ['POST'])]
    #[IsGranted(CandidatureVoter::DELETE, subject: 'candidature')]
    public function delete(Request $request, Candidature $candidature, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidature->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($candidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
    }
}
