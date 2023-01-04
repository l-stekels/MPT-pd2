<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UploadType;
use App\Repository\StatisticsRepository;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly FileService $fileService,
        private readonly StatisticsRepository $statisticsRepo,
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render(
            'index.html.twig',
            [
                'statistics' => $this->statisticsRepo->getTeamStatistics(),
            ]
        );
    }

    #[Route('/upload', name: 'upload')]
    public function upload(Request $request): Response
    {
        $form = $this->createForm(UploadType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile[] $uploadedFiles */
            $uploadedFiles = $form->get('files')->getData();
            $files = [];
            foreach ($uploadedFiles as $file) {
                $files[$file->getFilename()] = $file->getContent();
            }
            $errors = $this->fileService->process($files);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
