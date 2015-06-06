<?php

/**
 * Apishka social login provider router
 *
 * @uses \Apishka\EasyExtend\Router\ByKeyAbstract
 *
 * @author Alexander "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_ProviderRouter extends \Apishka\EasyExtend\Router\ByKeyAbstract
{
    /**
     * Is correct item
     *
     * @param \ReflectionClass $reflector
     *
     * @return bool
     */

    protected function isCorrectItem(\ReflectionClass $reflector)
    {
        if (!parent::isCorrectItem($reflector))
            return false;

        return $this->hasClassInterface($reflector, 'Apishka_SocialLogin_ProviderInterface');
    }

    /**
     * Get class variants
     *
     * @param \ReflectionClass $reflector
     * @param object           $item
     *
     * @return array
     */

    protected function getClassVariants(\ReflectionClass $reflector, $item)
    {
        return array(
            $item->getALias(),
        );
    }
}
