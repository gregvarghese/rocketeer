<?php
namespace Rocketeer\Commands;

class DeployDeployCommand extends BaseDeployCommand
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy:deploy';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deploy the website.';

	/**
	 * Execute the tasks
	 *
	 * @return array
	 */
	public function fire()
	{
		// Setup if necessary
		if (!$this->isSetup()) {
			$this->call('deploy:setup');
		}

		// Update current release
		$this->getReleasesManager()->updateCurrentRelease(time());

		$this->remote->run($this->getTasks());
		$this->call('deploy:cleanup');
	}

	/**
	 * The tasks to execute
	 *
	 * @return array
	 */
	public function tasks()
	{
		$currentReleasePath = $this->getReleasesManager()->getCurrentReleasePath();

		return array(
			// Clone release and update symlink
			$this->cloneRelease(),
			$this->removeFolder('current'),
			$this->updateSymlink(),

			// Run composer
			$this->gotoFolder($currentReleasePath),
			$this->runComposer(),

			// Set permissions
			"chmod -R +x " .$currentReleasePath.'/app',
			"chmod -R +x " .$currentReleasePath.'/public',
			"chown -R www-data:www-data " .$currentReleasePath.'/app',
			"chown -R www-data:www-data " .$currentReleasePath.'/public',
		);
	}

	////////////////////////////////////////////////////////////////////
	/////////////////////////////// HELPERS ////////////////////////////
	////////////////////////////////////////////////////////////////////

	/**
	 * Check if the remote server is set up
	 *
	 * @return boolean
	 */
	protected function isSetup()
	{
		$isSetup = false;
		$this->remote->run(array(
			'cd '.$this->getRocketeer()->getHomeFolder(),
		), function($remoteFolder) use (&$isSetup) {
			$isSetup = (bool) !str_contains($remoteFolder, 'no such file or directory');
		});

		return $isSetup;
	}

}
