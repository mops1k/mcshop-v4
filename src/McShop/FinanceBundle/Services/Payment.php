<?php
namespace McShop\FinanceBundle\Services;

use Doctrine\Common\Persistence\ManagerRegistry;
use McShop\FinanceBundle\Entity\Purse;
use McShop\FinanceBundle\Entity\Transactions;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Payment
{
    /** @var ManagerRegistry */
    private $doctrine;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var array */
    private $ik;

    /**
     * Payment constructor.
     * @param ManagerRegistry $doctrine
     * @param TokenStorageInterface $tokenStorage
     * @param array $ik
     */
    public function __construct(ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage, array $ik)
    {
        $this->tokenStorage = $tokenStorage;
        $this->doctrine = $doctrine;
        $this->ik = $ik;
    }

    /**
     * @param $param
     * @return mixed
     */
    public function ikSort($param)
    {
        $data['ik_co_id'] = $this->ik['shop_id'];
        // убирает параметры без /ik_/
        foreach ($param as $key => $value) {
            if (!preg_match('/ik_/', $key)) {
                continue;
            }
            $data[$key] = $value; // сохраняем параметры
        }

        return $data;
    }

    /**
     * интеркасса генератор контрольной цифровой подписи со стороны сервера
     * @param $param
     * @return bool
     */
    public function ikSign($param)
    {
        $data = $this->ikSort($param);
        $ikSign = $data['ik_sign']; // сохраняем приходящую переменную
        unset($data['ik_sign']); // удаляем придодащую переменную, для генирации подписи

        $key = ($data['ik_pw_via'] == 'test_interkassa_test_xts') ? $this->ik['test_key'] : $this->ik['key'];
        if ($data['ik_pw_via'] == 'test_interkassa_test_xts' && !$this->ik['testing']) {
            return false;
        }

        ksort($data, SORT_STRING); // сортируем массив
        array_push($data, $key); // внедряем переменуую $key в массив
        $signStr = implode(':', $data); // записываем массив в формат @string через :
        $sign = base64_encode(md5($signStr, true)); // хешируем подпись

        return ($sign == $ikSign) ? true : false;
    }

    public function payForm($amount)
    {
        $amount = (float) $amount;
        if ($amount <= 0) {
            /** заменить на сгенерированный урл ошибки платежа */
            return "/{$this->config['path']}/payment.php?reply=fail";
        }

        $purse = $this->doctrine
            ->getManagerForClass('McShopFinanceBundle:Purse')
            ->getRepository('McShopFinanceBundle:Purse')
            ->findOneByUser($this->tokenStorage->getToken()->getUser())
        ;

        if ($purse === null) {
            $purse = new Purse();
            $purse->setUser($this->tokenStorage->getToken()->getUser());
        }


        $transaction = new Transactions();
        $transaction
            ->setCash($amount)
            ->setPurse($purse)
            ->setStatus(Transactions::STATUS_IN_PROCCESS)
        ;

        $this->doctrine->getManagerForClass('McShopFinanceBundle:Purse')->persist($purse);
        $this->doctrine->getManagerForClass(get_class($transaction))->persist($transaction);
        $this->doctrine->getManagerForClass('McShopFinanceBundle:Purse')->flush();

        $paymentId = $transaction->getId();

        return sprintf(
            "https://sci.interkassa.com/?ik_co_id=%s&ik_pm_no=%d&ik_am=%d&ik_cur=%s&ik_desc=%s",
            $this->ik['shop_id'],
            $paymentId,
            $amount,
            $this->ik['cur'],
            $this->ik['desc'] . ' #' . $paymentId
        );
    }
}
