# MYMODULE FOR [DigitalProspects ERP CRM](https://www.DigitalProspects.org)

## Features

Description...

<!--
![Screenshot mymodule](img/screenshot_mymodule.png?raw=true "MyModule"){imgmd}
-->

Other modules are available on [Dolistore.com](https://www.dolistore.com).

## Translations

Translations can be define manually by editing files into directories *langs*.

<!--
This module contains also a sample configuration for Transifex, under the hidden directory [.tx](.tx), so it is possible to manage translation using this service.

For more informations, see the [translator's documentation](https://wiki.DigitalProspects.org/index.php/Translator_documentation).

There is a [Transifex project](https://transifex.com/projects/p/DigitalProspects-module-template) for this module.
-->

<!--

## Installation

### From the ZIP file and GUI interface

- If you get the module in a zip file (like when downloading it from the market place [Dolistore](https://www.dolistore.com)), go into
menu ```Home - Setup - Modules - Deploy external module``` and upload the zip file.

Note: If this screen tell you there is no custom directory, check your setup is correct:

- In your DigitalProspects installation directory, edit the ```htdocs/conf/conf.php``` file and check that following lines are not commented:

    ```php
    //$DigitalProspects_main_url_root_alt ...
    //$DigitalProspects_main_document_root_alt ...
    ```

- Uncomment them if necessary (delete the leading ```//```) and assign a sensible value according to your DigitalProspects installation

    For example :

    - UNIX:
        ```php
        $DigitalProspects_main_url_root_alt = '/custom';
        $DigitalProspects_main_document_root_alt = '/var/www/DigitalProspects/htdocs/custom';
        ```

    - Windows:
        ```php
        $DigitalProspects_main_url_root_alt = '/custom';
        $DigitalProspects_main_document_root_alt = 'C:/My Web Sites/DigitalProspects/htdocs/custom';
        ```

### From a GIT repository

- Clone the repository in ```$DigitalProspects_main_document_root_alt/mymodule```

```sh
cd ....../custom
git clone git@github.com:gitlogin/mymodule.git mymodule
```

### <a name="final_steps"></a>Final steps

From your browser:

  - Log into DigitalProspects as a super-administrator
  - Go to "Setup" -> "Modules"
  - You should now be able to find and enable the module

-->

## Licenses

### Main code

GPLv3 or (at your option) any later version. See file COPYING for more information.

### Documentation

All texts and readmes are licensed under GFDL.
