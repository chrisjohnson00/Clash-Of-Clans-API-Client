# Clash-Of-Clans-API-Client

## Vagrant environment setup

Install vagrant from vagrantup.com

Create a file called `github-oauth-token.txt` with your git hub oauth token, found on your settings page -> personal access tokens

Once you've got that all setup, it's super easy to create the dev environment... just run

    composer up
    
This will build the required environment for development.
To connect to this VM run

    composer ssh
    
This connects to your new vagrant vm, where you can do stuff!! WOOOHOOOO!

Once connected... run

    cd /vagrant
    composer install
    
And this will setup all the dependencies required!!!


## To run tests

You'll need to create a file in the same location as `composer.json` called `testKey.txt`, the contents should be your key/token.