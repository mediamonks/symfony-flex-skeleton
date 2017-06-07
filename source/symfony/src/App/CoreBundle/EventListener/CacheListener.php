<?php

namespace App\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CacheListener
{
    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!headers_sent()) {
            header_remove('Cache-Control');
        }
    }
}
