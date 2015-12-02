<?php

namespace Owncloud\Updater\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DisableNotShippedAppsCommand extends Command {

	protected function configure(){
		$this
				->setName('upgrade:disableNotShippedApps')
				->setDescription('Disable not shipped apps')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output){
		$this->container['utils.appmanager']->disableNotShippedApps($output);
	}

}
