<?php

use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 */

Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle(Loc::getMessage("CAR_BOOKING_INSTALL_TITLE"));

?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="post">
    <p><?= Loc::getMessage("CAR_BOOKING_INSTALL_TEST_DATA") ?></p>
    <input type="checkbox" name="install_test_data" value="Y" id="install_test_data">
    <label for="install_test_data"><?= Loc::getMessage("CAR_BOOKING_INSTALL_TEST_DATA_OPTION") ?></label>
    <br><br>
    <input type="submit" name="inst" value="<?= Loc::getMessage("CAR_BOOKING_INSTALL_BUTTON") ?>">
</form>