<?php
namespace McShop\NewsBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\NewsBundle\Entity\Post;
use McShop\NewsBundle\Form\PostType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class NewsCrudController
 * @package McShop\NewsBundle\Controller
 */
class NewsCrudController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        if (!$this->isGranted('ROLE_NEWS_ADD')) {
            throw $this->createAccessDeniedException();
        }
        $this->get('app.title')->setValue('news.add.title');

        $form = $this->getForm();

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($this->processForm($form)) {
                $this->addFlash('info', $this->get('translator')->trans('news.new_success'));
                return $this->redirectToRoute('mc_shop_news_view', [
                    'id'    => $form->getData()->getId(),
                ]);
            }
        }

        return $this->render(':Default/News:post.html.twig', [
            'form'  => $form->createView(),
            'header_title'  => $this->get('translator')->trans('news.add.title'),
        ]);
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Post $post, Request $request)
    {
        $isOwner = true;
        if ($post->getUser() !== $this->getUser()) {
            $isOwner = false;
        }

        if (!$this->isGranted('ROLE_NEWS_EDIT') && !$isOwner) {
            throw $this->createAccessDeniedException();
        }

        $this->get('app.title')->setValue('news.edit.title')->setAttributes([ '@id@' => $post->getId() ]);

        $form = $this->getForm($post);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($this->processForm($form)) {
                $this->addFlash('info', $this->get('translator')->trans('news.edit_success'));
                return $this->redirectToRoute('mc_shop_news_view', [
                    'id'    => $post->getId(),
                ]);
            }
        }

        return $this->render(':Default/News:post.html.twig', [
            'form'  => $form->createView(),
            'header_title'  => $this->get('translator')->trans('news.edit.title', [ '@id@' => $post->getId() ]),
        ]);
    }

    /**
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Post $post)
    {
        if (!$this->isGranted('ROLE_NEWS_REMOVE')) {
            throw $this->createAccessDeniedException();
        }

        $this->getDoctrine()->getManagerForClass(get_class($post))->remove($post);
        $this->getDoctrine()->getManagerForClass(get_class($post))->flush();

        $this->addFlash('info', $this->get('translator')->trans('news.remove_success'));

        return $this->redirectToRoute('mc_shop_news_homepage');
    }

    /**
     * @param Post|null $post
     * @return \Symfony\Component\Form\Form
     */
    private function getForm($post = null)
    {
        if ($post !== null && !$post instanceof Post) {
            throw new UnprocessableEntityHttpException('Wrong parameter for $post');
        }

        $form = $this->createForm(PostType::class, $post);
        return $form;
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    private function processForm(FormInterface $form)
    {
        if (!$form->isValid()) {
            $errors = $this->get('validator')->validate($form);
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }

            return false;
        }

        /** @var Post $post */
        $post = $form->getData();
        if ($post->getUser() === null) {
            $post->setUser($this->getUser());
        }

        $this->getDoctrine()->getManagerForClass(get_class($post))->persist($post);
        $this->getDoctrine()->getManagerForClass(get_class($post))->flush();

        return true;
    }
}