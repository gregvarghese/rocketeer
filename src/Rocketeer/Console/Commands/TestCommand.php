<?php
/*
 * This file is part of Rocketeer
 *
 * (c) Maxime Fabre <ehtnam6@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rocketeer\Console\Commands;

use Rocketeer\Abstracts\AbstractCommand;

/**
 * Run the tests on the server and displays the output
 *
 * @author Maxime Fabre <ehtnam6@gmail.com>
 */
class TestCommand extends AbstractCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy:test';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the tests on the server and displays the output';

	/**
	 * The tasks to execute
	 */
	public function fire()
	{
		$this->input->setOption('verbose', true);

		$this->fireTasksQueue('test');
	}
}