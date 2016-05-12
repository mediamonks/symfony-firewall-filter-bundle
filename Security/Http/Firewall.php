<?php

namespace MediaMonks\SecurityBundle\Security\Http;

use MediaMonks\SecurityBundle\Security\FirewallMap;
use Symfony\Component\Security\Http\Firewall as FirewallBase;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Firewall
 * @package MediaMonks\SecurityBundle\Security\Http
 * @author pawel@mediamonks.com
 */
class Firewall implements EventSubscriberInterface
{
    const CURRENT_FIREWALL_KEY = '_current_firewall';

    /**
     * @var FirewallMap
     */
    protected $map;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var \SplObjectStorage
     */
    protected $exceptionListeners;

    /**
     * Constructor.
     *
     * @param FirewallMap     $map        A FirewallMap instance
     * @param EventDispatcherInterface $dispatcher An EventDispatcherInterface instance
     */
    public function __construct(FirewallMap $map, EventDispatcherInterface $dispatcher)
    {
        $this->map = $map;
        $this->dispatcher = $dispatcher;
        $this->exceptionListeners = new \SplObjectStorage();
    }

    /**
     * Handles security.
     *
     * @param GetResponseEvent $event An GetResponseEvent instance
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // register listeners for this firewall
        list($listeners, $exceptionListener) = $this->map->getListeners($event->getRequest());
        $event->getRequest()->attributes->set(static::CURRENT_FIREWALL_KEY, $this->map->getLastPickedFirewall());

        if (null !== $exceptionListener) {
            $this->exceptionListeners[$event->getRequest()] = $exceptionListener;
            $exceptionListener->register($this->dispatcher);
        }

        // initiate the listener chain
        foreach ($listeners as $listener) {
            $listener->handle($event);

            if ($event->hasResponse()) {
                break;
            }
        }
    }

    public function onKernelFinishRequest(FinishRequestEvent $event)
    {
        $request = $event->getRequest();

        if (isset($this->exceptionListeners[$request])) {
            $this->exceptionListeners[$request]->unregister($this->dispatcher);
            unset($this->exceptionListeners[$request]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', 8),
            KernelEvents::FINISH_REQUEST => 'onKernelFinishRequest',
        );
    }
}