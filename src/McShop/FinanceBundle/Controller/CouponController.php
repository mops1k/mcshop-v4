<?php
namespace McShop\FinanceBundle\Controller;

use McShop\Core\Controller\BaseController;
use McShop\FinanceBundle\Entity\Coupon;
use McShop\FinanceBundle\Form\CouponCodeType;
use McShop\FinanceBundle\Form\CouponFilter;
use McShop\FinanceBundle\Form\CouponForm;
use McShop\FinanceBundle\Repository\CouponRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CouponController
 * @package McShop\FinanceBundle\Controller
 */
class CouponController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_COUPON_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(CouponForm::class);

        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if (!$form->isValid()) {
                $errors = $this->get('validator')->validate($form);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }

                return $this->redirectToRoute('mc_shop_finance_coupon_admin');
            }

            $generator = $this->get('mc_shop_finance.coupon_generator');
            $generator
                ->setAmount($form->get('amount')->getData())
                ->setCount($form->get('count')->getData())
                ->setDueDate($form->get('dueDate')->getData())
                ->generate()
            ;

            return $this->redirectToRoute('mc_shop_finance_coupon_admin');
        }

        $filterForm = $this->createForm(CouponFilter::class);
        $filterForm->handleRequest($request);

        $query = $this->getDoctrine()
            ->getManagerForClass('McShopFinanceBundle:Coupon')
            ->getRepository('McShopFinanceBundle:Coupon')
            ->findWithFilters($filterForm, CouponRepository::RETURN_QUERY)
        ;

        $adapter = new DoctrineORMAdapter($query);
        /** @var Coupon[] $posts */
        $coupons = new Pagerfanta($adapter);
        $coupons
            ->setMaxPerPage($request->get('per_page', 30))
            ->setCurrentPage($request->get('page', 1))
        ;

        return $this->render(':Default/Finance:coupon_list.html.twig', [
            'form'          => $form->createView(),
            'filterForm'    => $filterForm->createView(),
            'coupons'       => $coupons,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(CouponCodeType::class);
        $form->handleRequest($request);

        $code = $form->get('code')->getData();
        if (!$this->get('mc_shop_finance.coupon_generator')->activateCoupon($code)) {
            $this->addFlash('danger', $this->get('translator')->trans('finance.coupon.activation_error'));
            return $this->redirectToReferer();
        }

        $this->addFlash('danger', $this->get('translator')->trans('finance.coupon.activation_success'));

        return $this->redirectToReferer();
    }
}
