<?php

namespace Resque\Component\Core\Event;

use Resque\Component\Core\Event\EventDispatcherInterface;

/**
 * Resque simple event dispatcher
 *
 * Allows simple binding of a callable to events. @see http://php.net/manual/en/language.types.callable.php
 *
 * It is expected that you'll inject your own EventDispatcher, and manage the events in a way
 * that makes sense to you. This class is not intended to solve any event dispatching problems for you.
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array Array containing all registered callbacks, indexed by event name.
     */
    protected $listeners = array();

    /**
     * Dispatch an event
     *
     * @param string $event The event to dispatch to relevant listeners.
     * @param mixed $context The event context.
     */
    public function dispatch($event, $context)
    {
        if (false === isset($this->listeners[$event])) {

            return;
        }

        foreach ($this->listeners[$event] as $callback) {
            call_user_func($callback, $context);
        }
    }

    /**
     * Listen in on a given event to have a specified callback fired.
     *
     * @throws \InvalidArgumentException when $callback is not a callable.
     *
     * @param string $event The name of the event to listen for.
     * @param callable $callback Any callback callable by call_user_func_array.
     */
    public function addListener($event, $callback)
    {
        if (false === is_callable($callback)) {
            throw new \InvalidArgumentException('$callback must be callable');
        }

        if (false === isset($this->listeners[$event])) {
            $this->listeners[$event] = array();
        }

        $this->listeners[$event][] = $callback;
    }

    /**
     * Stop a given callback from listening on a specific event.
     *
     * @param string $event The name of the event to stop listening for.
     * @param mixed $callback The callback as defined when addListener() was called.
     */
    public function removeListener($event, $callback)
    {
        if (false === isset($this->listeners[$event])) {

            return;
        }

        $key = array_search($callback, $this->listeners[$event]);
        if ($key !== false) {
            unset($this->listeners[$event][$key]);

            if (0 === count($this->listeners[$event])) {
                unset($this->listeners[$event]);
            }
        }
    }

    /**
     * Get registered listeners.
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * Call all registered listeners.
     */
    public function clearListeners()
    {
        $this->listeners = array();
    }
}
