# pinnacle-sports-api
A PHP Client wrapper for the Pinnacle Sports API (https://www.pinnacle.com/en/api/manual)

You will need an API key to use this client. This is the Base64 value of UTF-8 encoded username:password

Add to project using Composer
-----------------------------

    composer require "netsensia/pinnacle-sports-api:1.0.0"
    
Usage
-----

    $client = new Client($apiKey);
    $sports = $client->getSports();
    $leagues = $client->getLeagues(33); // 33 is the id for tennis, as obtained from getSports()
    
A full list of available calls can be found by examining the ClientSpec.php file which contains the spec tests. The *json* directory contains examples of the data returned by the Companies House API.

Development
-----------

### Clone the repo and compose

    git clone git@github.com:netsensia/pinnacle-sports-api
    cd pinnacle-sports-api
    php composer.phar install

### Run the tests

Create a file called .apiKey in the root of the project and add your api key to it.

    bin/phpspec run --format=pretty -vvv --stop-on-failure
