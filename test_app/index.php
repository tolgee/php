<?php

use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Tolgee;
use Tolgee\Core\TolgeeConfig;

require __DIR__ . "/vendor/autoload.php";

$config = new TolgeeConfig();
$config->apiUrl = "http://tolgee:8080";
$config->apiKey = "this_is_dummy_api_key";
$config->mode = Modes::DEVELOPMENT;
$tolgee = new Tolgee($config);
$tolgee->setCurrentLang($_GET["lang"] ?: "en");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tolgee PHP test app</title>
</head>
<body>
<div id="loading" style="
        position: fixed;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
    Loading...
</div>
<script src="./node_modules/@tolgee/core/dist/tolgee.window.js"></script>
<script src="./node_modules/@tolgee/ui/dist/tolgee.window.js"></script>
<script>
    const tolgee = new window["@tolgee/core"].Tolgee({
        apiUrl: "http://localhost:8202",
        apiKey: "<?=$config->apiKey?>",
        mode: "<?=$config->mode?>",
        ui: window["@tolgee/ui"].UI,
    });
    tolgee.lang = "<?=$_GET["lang"] ?: "en"?>";
    tolgee.run().then(() => {
        document.getElementById("loading").style.display = "none";
    });
</script>
<a href="?lang=en">En<a> <a href="?lang=de">De<a>

                <h3>This is a text wrapped with tolgee php library: </h3>
                <p data-cy="basic-wrapped"><?= $tolgee->translate("sampleApp.hello_world!") ?></p>

                <h3>This is a text translated with tolgee php library: </h3>
                <p data-cy="basic-wrapped"><?= $tolgee->translate("sampleApp.english_text_one", [], true) ?></p>
</body>
</html>