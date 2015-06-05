<?php
/**
 * Barzahlen Payment Module (modified eCommerce)
 *
 * @copyright   Copyright (c) 2015 Cash Payment Solutions GmbH (https://www.barzahlen.de)
 * @author      Mathias Hertlein
 * @author      Alexander Diebler
 * @license     http://opensource.org/licenses/GPL-2.0  GNU General Public License, version 2 (GPL-2.0)
 */

defined('_VALID_XTC') or die('Direct Access to this location is not allowed.');

require_once("barzahlen/BarzahlenHttpClient.php");
require_once("barzahlen/BarzahlenPluginCheckRequest.php");
require_once("barzahlen/BarzahlenVersionCheck.php");

require_once(DIR_FS_LANGUAGES . $_SESSION['language'] . "/modules/payment/barzahlen.php");

$httpClient = new BarzahlenHttpClient();
$barzahlenVersionCheckRequest = new BarzahlenPluginCheckRequest($httpClient);
$barzahlenVersionCheck = new BarzahlenVersionCheck($barzahlenVersionCheckRequest);

try {
    if (MODULE_PAYMENT_BARZAHLEN_STATUS == "True" && !$barzahlenVersionCheck->isCheckedInLastWeek()) {
        $barzahlenVersionCheck->check(MODULE_PAYMENT_BARZAHLEN_SHOPID, PROJECT_VERSION);
        $displayUpdateAvailableMessage = $barzahlenVersionCheck->isNewVersionAvailable();
    } else {
        $displayUpdateAvailableMessage = false;
    }
} catch (Exception $e) {
    error_log('barzahlen/versioncheck: ' . $e, 3, DIR_FS_CATALOG . 'log/barzahlen.log');
    $displayUpdateAvailableMessage = false;
}

if ($displayUpdateAvailableMessage) {
    echo '<div style="background-color: #fdac00; border: 1px solid #ff0000; font-family: Verdana,Arial,sans-serif; font-size: 12px; padding: 5px;">';
    echo sprintf(MODULE_PAYMENT_BARZAHLEN_NEW_VERSION, $barzahlenVersionCheck->getNewestVersion(), $barzahlenVersionCheck->getNewestVersionUrl());
    echo '</div>';
}
