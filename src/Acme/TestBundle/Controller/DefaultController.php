<?php

namespace Acme\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($page = 1)
    {
        $manager = $this->getDoctrine()->getManager();
        /**
         * @var Query $query
         */
        $query = $manager->createQuery('SELECT a FROM AcmeBookBundle:Post a');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            5
        );
        $pagination->setUsedRoute('acme_test_homepage_page');
        $form = $this->createForm('post');
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $manager->persist($message);
            $manager->flush();

            return $this->redirect($this->generateUrl('acme_book_homepage'));
        }
        return $this->render('AcmeTestBundle:Default:index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView(),
        ));
    }
}
