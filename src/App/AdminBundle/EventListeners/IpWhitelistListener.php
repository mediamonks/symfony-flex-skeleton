<?php

namespace App\AdminBundle\EventListeners;

use IPSet\IPSet;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IpWhitelistListener
{
    /**
     * @var array
     */
    private $ipWhitelist;

    /**
     * @var string
     */
    private $adminDirectory;

    /**
     * IpWhitelistListener constructor.
     * @param array $ipWhitelist
     * @param string $adminDirectory
     */
    public function __construct(array $ipWhitelist, $adminDirectory)
    {
        $this->ipWhitelist = $ipWhitelist;
        $this->adminDirectory = $adminDirectory;
    }

    /**
     * @param GetResponseEvent $event
     * @throws \Exception
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
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