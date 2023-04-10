<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Translate Manager - JSON Translator</h1>
    <br>
</p>

This project is based on the Google API PHP Client library, which is licensed under the Apache License 2.0. Please see the [LICENSE](https://github.com/googleapis/google-api-php-client/blob/master/LICENSE) file for details.

_License_
Apache 2.0

This is a translator based on Google Sheets API and it supported Yii 2 Translate Manager exported JSON. It allows you to easily select a language to translate from and to, and then export a JSON file that can be directly imported into the Yii 2 language manager.

## Requirements

* [PHP 7.4](https://www.php.net/)

## Credentials Installation

1. Go to the [Google Cloud Console](https://console.cloud.google.com).
2. Create a new project (or select an existing one) from the project dropdown menu in the top navigation bar.
3. Go to the API & Services Dashboard and click on the "+ ENABLE APIS AND SERVICES" button.
4. Search for "Google Sheets API" and click on it.
5. Click on the "ENABLE" button to enable the API for your project.
6. Go to the "Credentials" tab in the left navigation menu and click on the "+ CREATE CREDENTIALS" button.
7. Choose "Service account" and enter a name for the service account.
8. Select the role "Project" > "Editor".
9. Click on the "CREATE" button to create the service account.
10. After creating the service account, click on the "CREATE KEY" button and select "JSON" as the key type. This will download a JSON file containing your private key.
11. Save the JSON file in a secure location on your local machine.
12. Copy & paste the JSON file into **web** folder.
13. Rename the JSON file to **keyfile.json**.

## Getting Started With Docker
   
Simply clone the repository and start the container

    docker-compose up -d
    
Update vendor packages

    compoert update OR composer install

You can then access the application through the following URL:

    http://localhost:8001


## Usage

To use the translator, open your web browser and navigate to http://localhost:8001 (or the IP address of your Docker container if running on a remote server). From there, select the language you want to translate from and the language you want to translate to, and click "Translate".

The translated JSON file will be automatically generated and can be downloaded from the web interface.

## Example
