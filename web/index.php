<?php

require_once __DIR__.'/../vendor/autoload.php';

use XO\Service\Game;
use XO\Controller\GameController;
use Silex\Provider\ServiceControllerServiceProvider;

class App extends Silex\Application
{
    use Silex\Application\TwigTrait;
}

$app = new App();

$app->register(new ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../src/XO/Views',
    ));

$gameService = new Game();

$app['game.controller'] = $app->share(function() use ($app, $gameService) {
        return new GameController($app, $gameService);
    });

$app->get('/', "game.controller:indexAction");
$app->get('/turn', "game.controller:indexJsonAction");

$app->run();