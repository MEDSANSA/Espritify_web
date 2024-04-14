<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;




#[Route('/club')]
class ClubController extends AbstractController
{
    #[Route('/', name: 'app_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {
            $clubs = $clubRepository->findAll();

           $qrCodeDirectory = $this->getParameter('kernel.project_dir') . '/public/qrcodes';
              if (!file_exists($qrCodeDirectory)) {
                  mkdir($qrCodeDirectory, 0777, true);
              }

              // Generate and save QR code images for each club
             foreach ($clubs as $club) {
                   // Ensure the Facebook URL is not empty
                   $fbUrl = $club->getPageFb();
                    $instaUrl = $club->getPageInsta();

                   if (empty($fbUrl)&&empty($instaUrl)) {
                       // Skip generating QR code if the Facebook URL is empty
                       continue;
                   }
                  // Instantiate QR code options
                             $options = new QROptions([
                                        'version' => 5, // QR code version
                                        // You can set other options here, such as error correction level, size, etc.
                                    ]);
        $qrCode = new QRCode($options);

                                     // Instantiate QR code with options
              $qrCodeData = $qrCode->render($fbUrl);
              $qrCodeDataInsta = $qrCode->render($instaUrl);


            // Check if the QR code data is SVG XML
            if (strpos($qrCodeData, 'data:image/svg+xml') === 0 && strpos($qrCodeDataInsta, 'data:image/svg+xml') === 0) {
                // Extract the SVG XML content from the data URI
                $svgContent = substr($qrCodeData, strpos($qrCodeData, 'base64,') + strlen('base64,'));
                $svgContentInsta = substr($qrCodeData, strpos($qrCodeDataInsta, 'base64,') + strlen('base64,'));

                // Decode the SVG XML content from base64
                $decodedSvgContent = base64_decode($svgContent);
                $decodedSvgContentInsta = base64_decode($svgContentInsta);


                // Save the decoded SVG XML content to a file
                $fbImagePath = 'qr_code_fb_' . uniqid() . '.svg';
                $instaImagePath = 'qr_code_insta_' . uniqid() . '.svg';

                file_put_contents($qrCodeDirectory . '/' . $fbImagePath, $decodedSvgContent);
                file_put_contents($qrCodeDirectory . '/' . $instaImagePath, $decodedSvgContentInsta);

                // Assign QR code image path to the club entity
                $club->qrCodeFbUrl = $fbImagePath;
                $club->qrCodeInstaUrl = $instaImagePath;


            } else {
                // Log an error or handle the invalid QR code data
                error_log('Invalid SVG XML data: ' . $qrCodeData);
                 error_log('Invalid SVG XML data: ' . $qrCodeDataInsta);

                // Optionally, set a default QR code image or display an error message
                $club->qrCodeFbUrl = 'default_qr_code.svg'; // Replace with your default image path
                $club->qrCodeInstaUrl = 'default_qr_code.svg'; // Replace with your default image path

            }

            }
        return $this->render('Admin/club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
            'uploads_directory' => $this->getParameter('uploads_directory'),

        ]);
    }

    #[Route('/new', name: 'app_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
         if (!is_null($club->getLogo())) {
                        $file = $form->get('logo')->getData();

                        $fileName = md5(uniqid()) . 'Property-.' . $file->guessExtension();
                        $file->move(
                            $this->getParameter('uploads_directory'),
                            $fileName
                        );

              $club->setLogo($fileName);

            $entityManager->persist($club);
            $entityManager->flush();

            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
          }
        }

        return $this->renderForm('/Admin/club/new.html.twig', [
            'club' => $club,
            'form' => $form,
            'uploads_directory' => $this->getParameter('uploads_directory'),
        ]);
    }

    #[Route('/{id}', name: 'app_club_show', methods: ['GET'])]
    public function show(Club $club): Response
    {
        return $this->render('Admin/club/show.html.twig', [
            'club' => $club,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {   $clubImageUrl = $club->getLogo();
        $form = $this->createForm(ClubType::class, $club, ['current_logo_path' => $clubImageUrl]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!is_null($club->getLogo())) {
                $file = $form->get('logo')->getData();

                $fileName = md5(uniqid()) . 'Property-.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );

                $club->setLogo($fileName);

                $entityManager->flush();

                return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        dump($request->request->all());

        return $this->renderForm('Admin/club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_club_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $entityManager->remove($club);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }

     #[Route('generate-qr-code/{id}', name: 'generate_qr_code')]
    public function generateQrCodeAction($id,ClubRepository $clubRepository)
        {
        $club = $clubRepository->find($id);

         if (!$club) {
                    throw $this->createNotFoundException('Club not found');
                }
         // Get the URL from the Club entity
        $url = $club->getPageFb(); // Assuming you want the Facebook
        $qrCode = new QrCode($url);


            // Set additional options if needed
            // For example, to set the size of the QR code:
            // $qrCode->setSize(300);

            // Return the QR code image as a response
            return new Response($qrCode->writeString(), 200, ['Content-Type' => $qrCode->getContentType()]);
        }


}

