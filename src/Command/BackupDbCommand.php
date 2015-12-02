<?php

namespace Owncloud\Updater\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BackupDbCommand extends Command {

	protected function configure(){
		$this
				->setName('upgrade:backupDb')
				->setDescription('Backup DB (optionally)')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output){
	}

}
