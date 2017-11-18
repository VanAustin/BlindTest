<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\User;
use AppBundle\Entity\Category;

class AdminController extends Controller
{

    /**
     * @Route("/admin/tags", name="tags")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminTagsAction(EntityManagerInterface $em)
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('admin/tags.html.twig', [
            'categories' => $categories,
        ]);
    }

     /**
     * @Route("admin/new_tag", name="new_tag")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminCategNewAction(Request $request, EntityManagerInterface $em)
    {
        $category = new Category;

        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie ' . $category->getCategoryName() . ' créée avec succès');
            return $this->redirectToRoute('tags');
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Une erreur est survenue');
        }
        return $this->render('admin/new_tag.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/edit/{id}", name="edit_tag")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function tagEditAction(Category $category = null, Request $request, EntityManagerInterface $em)
    {
        if($category === null) {
            throw new NotFoundHttpException('Cette catgéorie n\'existe pas...');
        }
        $deleteForm = $this->createDeleteForm($category);
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Catégorie ' . $category->getCategoryName() . ' éditée avec succès');
            return $this->redirectToRoute('tags');
        }
        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('danger', 'Une erreur est survenue');
        }
        return $this->render('admin/edit_tag.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

        /**
         * @Route("admin/delete/{id}", name="delete_tag")
         * @Method({"POST"})
         * @Security("has_role('ROLE_ADMIN')")
         */
        public function categoryDeleteAction(Category $category= null, Request $request, EntityManagerInterface $em)
        {
            $category= $this->getDoctrine()->getRepository(Category::class)
                ->find($request->request->get('id'));

            if($category=== null)
            {
                throw new NotFoundHttpException('Cette categorie n\'existe pas...');
            }

            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie ' . $category->getCategoryName() . ' supprimée avec succès');


            return $this->redirectToRoute('tags');
        }

    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_tag', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



}
