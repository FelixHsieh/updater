<?php

use Pimple\Container;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\StreamOutput;
use Owncloud\Updater\Console\Application;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Owncloud\Updater\Utils\Locator;
use Owncloud\Updater\Utils\ConfigReader;
use Owncloud\Updater\Utils\ConfigWriter;
use Owncloud\Updater\Utils\Fetcher;
use Owncloud\Updater\Utils\FilesystemHelper;
use Owncloud\Updater\Command\BackupDataCommand;
use Owncloud\Updater\Command\BackupDbCommand;
use Owncloud\Updater\Command\CheckSystemCommand;
use Owncloud\Updater\Command\CleanCacheCommand;
use Owncloud\Updater\Command\DbUpgradeCommand;
use Owncloud\Updater\Command\DetectCommand;
use Owncloud\Updater\Command\DisableNotShippedAppsCommand;
use Owncloud\Updater\Command\EnableNotShippedAppsCommand;
use Owncloud\Updater\Command\ExecuteCoreUpgradeScriptsCommand;
use Owncloud\Updater\Command\InfoCommand;
use Owncloud\Updater\Command\MaintenanceModeCommand;
use Owncloud\Updater\Command\PostUpgradeCleanupCommand;
use Owncloud\Updater\Command\PostUpgradeRepairCommand;
use Owncloud\Updater\Command\PreUpgradeRepairCommand;
use Owncloud\Updater\Command\RestartWebServerCommand;
use Owncloud\Updater\Command\UpdateConfigCommand;
use Owncloud\Updater\Command\UpgradeShippedAppsCommand;
use Owncloud\Updater\Command\StartCommand;

$c = new Container();

/*
  $c['parameters'] = [
  'locator.file' => 'locator.xml'
  ];
 */

$c['guzzle.httpClient'] = function($c){
	return new Client();
};

$c['utils.locator'] = function($c){
	return new Locator(CURRENT_DIR);
};

$c['utils.filesystemhelper'] = function($c){
	return new FilesystemHelper();
};

$c['logger.output'] = function($c){
	$stream = fopen(CURRENT_DIR . '/update.log', 'a+');
	if ($stream === false){
		return new NullOutput();
	} else {
		return new StreamOutput($stream, StreamOutput::VERBOSITY_DEBUG, false);
	}
};
$c['logger'] = function($c){
	return new ConsoleLogger($c['logger.output']);
};
$c['utils.configReader'] = function($c){
	return new ConfigReader($c['utils.locator']);
};
$c['utils.configWriter'] = function($c){
	return new ConfigWriter($c['utils.locator']);
};
$c['utils.fetcher'] = function($c){
	return new Fetcher($c['guzzle.httpClient'], $c['utils.locator'], $c['utils.configReader']);
};

$c['command.backupData'] = function($c){
	return new BackupDataCommand();
};
$c['command.backupDb'] = function($c){
	return new BackupDbCommand();
};
$c['command.checkSystem'] = function($c){
	return new CheckSystemCommand();
};
$c['command.cleanCache'] = function($c){
	return new CleanCacheCommand();
};
$c['command.dbUpgrade'] = function($c){
	return new DbUpgradeCommand();
};
$c['command.detect'] = function($c){
	return new DetectCommand($c['utils.fetcher'], $c['utils.configReader']);
};
$c['command.disableNotShippedApps'] = function($c){
	return new DisableNotShippedAppsCommand();
};
$c['command.enableNotShippedApps'] = function($c){
	return new EnableNotShippedAppsCommand();
};
$c['command.executeCoreUpgradeScripts'] = function($c){
	return new ExecuteCoreUpgradeScriptsCommand($c['utils.locator']);
};
$c['command.info'] = function($c){
	return new InfoCommand();
};
$c['command.maintenaceMode'] = function($c){
	return new MaintenanceModeCommand($c['utils.locator']);
};
$c['command.postUpgradeCleanup'] = function($c){
	return new PostUpgradeCleanupCommand();
};
$c['command.postUpgradeRepair'] = function($c){
	return new PostUpgradeRepairCommand();
};
$c['command.preUpgradeRepair'] = function($c){
	return new PreUpgradeRepairCommand();
};
$c['command.restartWebServer'] = function($c){
	return new RestartWebServerCommand();
};
$c['command.updateCoreCofig'] = function($c){
	return new UpdateConfigCommand();
};
$c['command.upgradeShippedApps'] = function($c){
	return new UpgradeShippedAppsCommand();
};
$c['command.start'] = function($c){
	return new StartCommand();
};

$c['commands'] = function($c){
	return [
		$c['command.backupData'],
		$c['command.backupDb'],
		$c['command.checkSystem'],
		$c['command.cleanCache'],
		$c['command.dbUpgrade'],
		$c['command.detect'],
		$c['command.disableNotShippedApps'],
		$c['command.enableNotShippedApps'],
		$c['command.executeCoreUpgradeScripts'],
		$c['command.info'],
		$c['command.maintenaceMode'],
		$c['command.postUpgradeCleanup'],
		$c['command.postUpgradeRepair'],
		$c['command.preUpgradeRepair'],
		$c['command.restartWebServer'],
		$c['command.updateCoreCofig'],
		$c['command.upgradeShippedApps'],
		$c['command.start'],
	];
};

$c['application'] = function($c){
	$application = new Application();
	$application->setContainer($c);
	$application->addCommands($c['commands']);
	return $application;
};

return $c;
