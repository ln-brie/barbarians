<?php

namespace App\Controller;

use App\Form\StatsForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

final class FormController extends AbstractController
{
    #[Route('/form', name: 'form-stats')]
    public function form_stats(Request $request): Response
    {
        $form = $this->createForm(StatsForm::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $stats = $form->getData();
            // ... perform some action, such as saving the task to the database

            // 🔥 The magic happens here! 🔥
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->renderBlock('form/index.html.twig', 'success_stream');
            }

            // If the client doesn't support JavaScript, or isn't using Turbo, the form still works as usual.
            // Symfony UX Turbo is all about progressively enhancing your applications!
            return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('form/index.html.twig', [
            'form' => $form,
        ]);
    }
}
