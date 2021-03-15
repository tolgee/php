## Tolgee PHP Integration

[<img src="https://raw.githubusercontent.com/tolgee/documentation/cca5778bcb8f57d28a03065d1927fcea31d0b089/tolgee_logo_text.svg" alt="Tolgee Toolkit" />](https://toolkit.tolgee.io)

Core library of Tolgee localization toolkit. For more information about Tolgee, visit our documentation website
[https://toolkit.tolgee.io](toolkit.tolgee.io).

## Installation

    composer require tolgee

## Usage

To use Tolgee with PHP, start with creating TolgeeConfig class instance and Tolgee class instance.

    <?php

    use Tolgee\Core\Enums\Modes;
    use Tolgee\Core\Tolgee;
    use Tolgee\Core\TolgeeConfig;
    
    $config = new TolgeeConfig();
    $config->apiKey = "your api key"
    $config->apiUrl = "your api url"
    $config->mode = Modes::DEVELOPMENT;
    
    $tolgee = new Tolgee($config);

Then you can simply use Tolgee to translate strings:

    $tolgee->translate("hello_world");

To learn more, check [our docs](https://toolkit.tolgee.io/docs/web/using_with_php).