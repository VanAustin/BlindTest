<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Liste;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Entity\Category;


class ListingController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("has_role('ROLE_USER')")
     */
    public function homepageAction(UserInterface $user = null, Request $request, EntityManagerInterface $em)
    {
        $task= $user->getTasks();
        $liste = $user->getListes();
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('listing/index.html.twig', [
            'task' => $task,
            'liste' => $liste,
            'categories'=> $categories,
        ]);
    }

/*******************************************************TASKS*************************************************/

    /**
    * @Route("/new_task", name="new_task")
    * @Method({"GET", "POST"})
     */
    public function newTaskAction(Request $request, UserInterface $user, EntityManagerInterface $em)
    {

      $task = new Task;

      // Création du formulaire d'ajout
      $form = $this->createForm('AppBundle\Form\TaskType', $task);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid())
      {
        $task->setAuthor($user);
        $em->persist($task);
        $em->flush();
        $this->addFlash('success', 'Tâche créée avec succès');

        return $this->redirectToRoute('homepage', ['id'=> $task->getId()]);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Une erreur est survenue');
        }

         return $this->render('listing/new_task.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("task/edit/{id}", name="edit_task")
     * @Method({"GET", "POST"})
     */
    public function taskEditAction(Task $task = null, Request $request, EntityManagerInterface $em)
    {
        if($task === null)
        {
            throw new NotFoundHttpException('Cette tâche n\'existe pas...');
         }

        $deleteForm = $this->createDeleteForm($task);
        $form = $this->createForm('AppBundle\Form\TaskType', $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'La tâche ' . $task->getTitle() . ' éditée avec succès');

            return $this->redirectToRoute('homepage');
         }

        if($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('danger', 'Une erreur est survenue');
        }
        return $this->render('task/edit.html.twig', [
            'task'=> $task,
            'form' => $form->createView(),
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    //Changement de statut
    /**
     * @Route("/task/{id}/{status}",
     * name="task_set_status",
     * requirements={"id": "\d+", "status": "done|undone"}
     * )
     * @Method("GET")
     */
    public function taskSetStatusAction(Request $request, Task $task = null, $status, EntityManagerInterface $em)
    {
        // Si la tâche n'existe pas
        if($task === null) {
            throw $this->createNotFoundException('Tâche non trouvée');
        }
        // On modifie le statut de la tâche
        $task->setStatus($status);
        $em->flush();
        $this->addFlash('success', 'Statut mis à jour.');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("task/delete/{id}", name="delete_task")
     * @Method({"POST"})
     */
    public function taskDeleteAction(Task $task= null, Request $request, EntityManagerInterface $em)
    {
        $task = $this->getDoctrine()->getRepository(Task::class)
            ->find($request->request->get('id'));

        if($task === null)
        {
            throw new NotFoundHttpException('Cette tâche n\'existe pas...');
        }

        $em->remove($task);
        $em->flush();
        $this->addFlash('success', 'La tâche ' . $task->getTitle() . ' supprimée avec succès');


        return $this->redirectToRoute('homepage');
    }


/*****************************************************CATEGORIES***********************************************************/

    /**
      * Changer la catégorie d'une tâche
      *
      * @Route("task/categ/{id}", name="change_categ")
      */
    public function changeCategAction(Request $request, UserInterface $user = null, EntityManagerInterface $em, Task $task = null)
    {
        if($task === null) {
            throw new NotFoundHttpException('Cette tâche n\'existe pas...');
        }
        if ($request->request->get('change-categ') !== null) {
            $task->setCategories($em->getRepository(Category::class)->findOneById($request->request->get('categ')));
            $em->flush();
        }
        return $this->redirectToRoute('my_task', array(
            'id' => $user->getId(),
        ));
    }
}
