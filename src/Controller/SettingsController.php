<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Setting;
use App\Form\SettingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/settings')]
class SettingsController extends AbstractController
{
    #[Route('/', name: 'app_settings', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Setting::class);
        $settings = $repository->findAll();

        $form = $this->createForm(SettingType::class, null, ['settings' => $settings]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($settings as $setting) {
                $key = $setting->getKey();
                $value = $form->get($key->value)->getData();
                $setting->setValue($value);
                $setting->setUpdatedAt(new \DateTimeImmutable());
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_settings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('settings/edit.html.twig', [
            'setting' => $settings,
            'form' => $form,
        ]);
    }
}
