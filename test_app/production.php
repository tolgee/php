<?php

use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Tolgee;
use Tolgee\Core\TolgeeConfig;

require __DIR__ . "/vendor/autoload.php";

$config = new TolgeeConfig();
$config->apiUrl = "http://tolgee:8080";
$config->apiKey = "this_is_dummy_api_key";
$config->mode = Modes::PRODUCTION;
$config->localFilesAbsolutePath = __DIR__ . "/i18n/";
$tolgee = new Tolgee($config);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tolgee PHP test app</title>
</head>
<body>
<h3>This is a text translated with tolgee php library</h3>
<p data-cy="basic-wrapped"><?= $tolgee->translate("sampleApp.hello_world!") ?></p>
</body>
</html>