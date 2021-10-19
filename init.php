<?php

namespace Project;

use Exception;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * PHP autoloader
 * This function will autoload classes from given namespace
 * @param string $className The namespace class to load
 * @return void
 * @throws Exception if class is not found
 */
spl_autoload_register(function (string $className) {
	//The list of all the namespaces folders
	$availableNamespaces = [
		'Models' => __DIR__ . '/models',
		'Controllers' => __DIR__ . '/controllers',
		'Core' => __DIR__ . '/core',
	];

	//We split the namespace by the namespace separator
	$namespace = explode('\\', $className);
	if ($namespace[0] !== 'Project')
		throw new Exception('Class name is not in namespace Project: ' . $className);

	//We iterate through all the available directories and if it match one we include it
	$included = false;
	foreach ($availableNamespaces as $availableNamespace => $dir) {
		if ($namespace[1] === $availableNamespace && file_exists($dir . '/' . $namespace[2] . '.php')) {
			include $dir . '/' . $namespace[2] . '.php';
			$included = true;
		}
	}
	//If not we check if it is in the root directory and if yes we include it
	if (!$included && file_exists(__DIR__ . '/' . $namespace[1] . '.php')) {
		include __DIR__ . '/' . $namespace[1] . '.php';
		$included = true;
	}
	//If nothing is included, the we throw an exception
	if (!$included)
		throw new Exception('Class ' . $className . ' not found');
});

use Project\Controllers\ControllerManager;
// use Project\Models\UserModel;

// $modelManager = new ModelManager();
// $modelManager->verify();
// $modelManager->init();

// $user = UserModel::create([
// 	'm_password' => 'blabla',
// 	'm_username' => 'Théodore',
// 	'm_email' => 'prevottheodore@gmail.com',
// ])->save();
// print_r($user);

// $user = UserModel::findOne(1);
// $user->m_username = 'Théodazdore';
// $user->save();
// $user->print();
// $user->remove();
// print_r(UserModel::findOne(345));
$controllerManager = new ControllerManager();
$controllerManager->handleRequest();