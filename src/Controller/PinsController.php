<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{

    /**
     * @Route("/pins", name="app_pins")
     */

    // public function index(EntityManagerInterface $em): Response
    // {
    //     // $repos = $em->getRepository('App\Entity\Pin'); OR
    //     $repos = $em->getRepository(Pin::class);
    //     $pins = $repos->findAll();
    //     return $this->render('pins/index.html.twig', [
    //         'pins' => $pins
    //     ]);
    // }
    
    // OU        // $repos = $em->getRepository('App\Entity\Pin'); OR


    public function index(PinRepository $repos): Response
    {
        $pins = $repos->findAll();
        return $this->render('pins/index.html.twig', [
            'pins' => $pins
        ]);
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */

    // public function createPins(Request $request, EntityManagerInterface $em){
    //     if($request->isMethod('POST')){
    //         // $request->request == $_POST
    //         // $request->query == $_GET
    //         $data=$request->request->all();

    //         if($this->isCsrfTokenValid('pins_create', $data['_token'])){
    //             $pin = new Pin;
    //             $pin->setTitle($data['title']);
    //             $pin->setDescription($data['description']);
    //             $em->persist($pin);
    //             $em->flush();
    //         }
    //         // return $this->redirect("/pins");
    //         // OR
    //         // return $this->redirect($this->generateUrl('app_pins'));
    //         // OR
    //         return $this->redirectToRoute('app_pins');
    //     }
    //     return $this->render('pins/create.html.twig');
    // }
    // OR
    public function createPins(Request $request, EntityManagerInterface $em){
        $pin = new Pin;
        $form = $this->createFormBuilder($pin)
            ->add('title', TextType::class, ['attr'=>['autofocus'=>true]])
            ->add('description', TextareaType::class, ['attr'=>['cols'=>50, 'rows'=>5]])
            // ->add('submit', SubmitType::class, ['label'=>'Create Pin'])
            ->getForm()
        ;

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            // $data = $form->getData();
            // $pin->setTitle($data['title']);
            // $pin->setDescription($data['description']);
            $em->persist($pin);
            $em->flush();
            return $this->redirectToRoute('app_pins');
            // Reiriger vers la detail du pin ki vien de creer
            // return $this->redirectToRoute('app_pins_detail', ['id'=> $pin->getId()]);
        }
        return $this->render('pins/create.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_detail")
     */
    public function show(PinRepository $repos, $id){
        $pin = $repos->find($id);
        if(! $pin){
            throw $this->createNotFoundException('Pin '.$id.' not found');
        }
        return $this->render('pins/show.html.twig', compact('pin'));
    }
}