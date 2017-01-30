<?php
namespace McShop\UserBundle\Services\CodeHandler;

use McShop\UserBundle\Entity\Token;

class RecoverHandler extends AbstractHandler
{

    /**
     * @param Token $token
     * @return bool
     */
    public function handle(Token $token)
    {
        return true;
    }
}
