<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			$modelPath = $modx->getOption('lmims.core_path', null, $modx->getOption('core_path') . 'components/lmims/') . 'model/';
			$modx->addPackage('lmims', $modelPath);
			$manager = $modx->getManager();

			$objects = array(
				'lmims',
			);
      $dontRemoveObjects = array(
//        'lmimsSomeClass',
      );
      $removeObjects = array(
//        'lmimsAnotherSomeClass',
      );

      foreach ($objects as $tmp) {
        if (!in_array($tmp, $dontRemoveObjects) || in_array($tmp, $removeObjects)) {
          $manager->removeObjectContainer($tmp);
        }
        $manager->createObjectContainer($tmp);
			}

      $modx->removeExtensionPackage('lmims');
      $modx->addExtensionPackage('lmims', $modx->getOption('lmims.core_path', null, '[[++core_path]]components/lmims/') .'model/');

      $level = $modx->getLogLevel();
      $modx->setLogLevel(xPDO::LOG_LEVEL_FATAL);
      $modx->setLogLevel($level);
      break;

		case xPDOTransport::ACTION_UPGRADE:
			break;

		case xPDOTransport::ACTION_UNINSTALL:
      $modx->removeExtensionPackage('lmims');
			break;
	}
}
return true;
