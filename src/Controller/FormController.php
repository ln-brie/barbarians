<?php

namespace App\Controller;

use App\Form\CombatForm;
use App\Form\Model\Attributs;
use App\Form\Model\Combat;
use App\Form\Model\CustomFormData;
use App\Form\Model\Perso;
use App\Form\OrigineForm;
use App\Form\StatsForm;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormController extends AbstractController
{

    public function __construct(private RequestStack $requestStack) {}


    #[Route('/attributs', name: 'attributs-form')]
    public function form_stats_attributs(Request $request): Response
    {
        $request->getSession()->start();

        $personnage = new Perso;
        $formDataAttributs = new Attributs;
        $form = $this->createForm(StatsForm::class, $formDataAttributs);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personnage->attributs = $form->getData();
            $request->getSession()->set('perso', $personnage);

            return $this->redirectToRoute('combat_form');
        }

        return $this->render('form/attributs.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('combat', name: 'combat_form')]
    public function generateCombatForm(Request $request)
    {
        $personnage = $request->getSession()->get('perso');

        $formDataCombat = new Combat;
        $combatForm = $this->createForm(CombatForm::class, $formDataCombat);
        $combatForm->handleRequest($request);
        if ($combatForm->isSubmitted() && $combatForm->isValid()) {
            $personnage->combat = $combatForm->getData();
            $request->getSession()->set('perso', $personnage);

            return $this->redirectToRoute('origine_form');
        }

        return $this->render('form/combat.html.twig', ['attributs' => $personnage->attributs, 'combatForm' => $combatForm]);
    }


    #[Route('origine', name: 'origine_form')]
    public function generateOrigineForm(Request $request)
    {

        $personnage = $request->getSession()->get('perso');

        $origines = file_get_contents('../assets/files/origines.json');
        $origines = json_decode($origines);
        $origineForm = $this->createForm(OrigineForm::class, options: ['origines' => $origines]);
        $origineForm->handleRequest($request);

        if ($origineForm->isSubmitted() && $origineForm->isValid()) {
            $personnage->origine = $origineForm->getData();
            dd($personnage);
        }
        return $this->render('form/origine.html.twig', ['personnage' => $personnage, 'origineForm' => $origineForm]);
    }
}
