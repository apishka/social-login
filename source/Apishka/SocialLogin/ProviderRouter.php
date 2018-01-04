<?php declare(strict_types = 1);

/**
 * Apishka social login provider router
 */
class Apishka_SocialLogin_ProviderRouter extends \Apishka\EasyExtend\Router\ByKeyAbstract
{
    protected function isCorrectItem(\ReflectionClass $reflector): bool
    {
        return $this->hasClassInterface($reflector, Apishka_SocialLogin_ProviderInterface::class);
    }

    protected function getClassVariants(\ReflectionClass $reflector, $item): array
    {
        return array(
            $item->getALias(),
        );
    }
}
