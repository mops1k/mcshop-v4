<?php
namespace McShop\NewsBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\NewsBundle\Entity\Commentary;
use McShop\NewsBundle\Form\CommentaryType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommentaryCrudController
 * @package McShop\NewsBundle\Controller
 */
class CommentaryCrudController extends BaseController
{
    /**
     * @param Commentary $commentary
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Commentary $commentary, Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $isOwner = $commentary->getUser() === $this->getUser();
        if (!$this->isGranted('ROLE_COMMENTARY_EDIT') && !$isOwner) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('news.comments.edit_title');

        $form = $this->createForm(CommentaryType::class, $commentary);
        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if (!$form->isValid()) {
                $errors = $this->get('validator')->validate($form);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->render(':Default/News:commentary.html.twig', [
                    'form'  => $form->createView(),
                ]);
            }

            $this->getDoctrine()->getManagerForClass(get_class($commentary))->persist($commentary);
            $this->getDoctrine()->getManagerForClass(get_class($commentary))->flush();

            $this->addFlash('info', $this->get('translator')->trans('news.comments.edit_success'));

            return $this->redirectToRoute('mc_shop_news_view', [
                'id'    => $commentary->getNews()->getId(),
            ]);
        }

        return $this->render(':Default/News:commentary.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @param Commentary $commentary
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Commentary $commentary)
    {
        if (!$this->isGranted('ROLE_COMMENTARY_REMOVE')) {
            throw $this->createAccessDeniedException();
        }

        $this->getDoctrine()->getManagerForClass(get_class($commentary))->remove($commentary);
        $this->getDoctrine()->getManagerForClass(get_class($commentary))->flush();

        $this->addFlash('info', $this->get('translator')->trans('news.comments.remove_success'));

        return $this->redirectToRoute('mc_shop_news_view', [
            'id'    => $commentary->getNews()->getId(),
        ]);
    }
}