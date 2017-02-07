# Api Sdk of Starred
The Api Sdk of Starred is just a bunch of acronyms to make integration life
a tad easier. The project is split in 2 parts, the `App` itself and its `Tests`

## Tests
The test automagically run with docker and run only the Unit tests out of the
box. They will test the interface itself without actually making the request.
They can be executed as simple as going to the root of your checkout and running:

    ./Test/run.sh

If you're in doubt whether our api calls actually work you can run our `System`
tests as well. For these you have to set up the configuration files located in
`Test/Config`. There are example configurations which you can setup with your
own account details and save them as follows:

    Auth.example.ini => Auth.ini
    Invitation.example.ini => Invitation.ini

Now if you uncomment the line follwing `# Run system test` the actual system
tests will be run in the same fashion as the regular tests (And you'll be sending
real test invites too, hooray!).

## App
The App itself contains the components which can be integrated into your own
code. Again, your credentials need to be setup which you can do in `App/Config`.
Everything is PHP based so this should get you started:

    <?php
    // Init autoloading
    require 'Autoload.php';
    new \StarredSdk\Autoload();
    // Send an invitation to a single recipient
    (new \StarredSdk\Invitation())->singleRecipient(/*your parameters*/)
