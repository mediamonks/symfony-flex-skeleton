<?php

namespace App\AdminBundle\EventListener;

use IPSet\IPSet;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IpWhitelistListener
{
    /**
     * @var string
     */
    private $adminDirectory;

    /**
     * @var array
     */
    private $ipWhitelist;

    /**
     * @param $adminDirectory
     * @param array $ipWhitelist
     */
    public function __construct($adminDirectory, array $ipWhitelist = null)
    {
        $this->adminDirectory = $adminDirectory;
        $this->ipWhitelist = $ipWhitelist;
    }

    /**
     * @param GetResponseEvent $event
     * @throws \Exception
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (empty($this->ipWhitelist)) {
            return;
        }

        $request = $event->getRequest();
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $ipSet = new IPSet($this->ipWhitelist);

        $path = substr($request->getPathInfo(), 1, strlen($this->adminDirectory));
        if ($path === $this->adminDirectory && !$ipSet->match($request->getClientIp())) {
            throw new AccessDeniedHttpException(sprintf('Access from IP address "%s" is denied.', $request->getClientIp()));
        }
    }
}
