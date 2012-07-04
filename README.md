# Slim Framework Skeleton Application

Use this skeleton application to quickly setup and start working on a new Slim Framework application. This application uses the latest Slim and Slim-Extras repositories. It also uses Sensio Labs' [Twig](http://twig.sensiolabs.org) template library.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install Composer

If you have not installed Composer, do that now. I prefer to install Composer globally in `/usr/local/bin`, but you may also install Composer locally in your current working directory. For this tutorial, I assume you have installed Composer locally.

<http://getcomposer.org/doc/00-intro.md#installation>

## Install the Application

After you install Composer, run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace <code>[my-app-name]</code> with the desired directory name for your new application. You'll want to point your virtual host document root to your new application's `public/` directory.

That's it! Now go build something cool.
