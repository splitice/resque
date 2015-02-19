<?php

namespace Resque\Component\Core\Event;

interface EventDispatcherInterface
{
    /**
     * Dispatch an event
     *
     * @param string $eventName The name of the event being dispatched.
     * @param mixed $eventContext The context (generally an object) for this event.
     */
    public function dispatch($eventName, $eventContext);
}
