<?php namespace Wasp\Events\Listeners;

use Symfony\Component\HttpKernel\Event\GetResponseEvent,
	Wasp\ShieldWall\Exceptions,
	Wasp\DI\DependencyInjectionAwareTrait;


/**
 * Event listener for the shield wall module
 *
 * @package Wasp
 * @subpackage Listeners
 * @author Dan Cox
 */
class ShieldWallListener
{

	use DependencyInjectionAwareTrait;

	/**
	 * The response event
	 *
	 * @var \Symfony\Component\HttpKernel\Event\GetResponseEvent
	 */
	protected $event;

	/**
	 * The name of the current route
	 *
	 * @var String
	 */
	protected $currentRoute;

	/**
	 * An array of framework settings defined by the Profile
	 *
	 * @var Array
	 */
	protected $settings;

	/**
	 * Event for on kernel request
	 *
	 * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
	 * @return void
	 * @author Dan Cox
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		$this->event = $event;
		$this->settings = (!is_null($this->DI->get('profile')) ? $this->DI->get('profile')->settings() : Array());

		if ($this->event->isMasterRequest() && $this->DI->has('shield'))
		{
			$this->currentRoute = $this->event->getRequest()->get('_route');

			try {

				$this->DI->get('shield')->request($this->currentRoute);

			} catch (Exceptions\AuthenticationException $e) {

				$response = $this->DI->get('response')
								->redirect(isset($this->settings['auth']['login']) ? $this->settings['auth']['login'] : '/login');

				$this->event->setResponse($response);
			}
		}
	}

} // END class ShieldWallListener
