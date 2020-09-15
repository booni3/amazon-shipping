<?php

namespace Booni3\AmazonShipping;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Booni3\AmazonShipping\Skeleton\SkeletonClass
 */
class AmazonShippingFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'amazon-shipping';
    }
}
