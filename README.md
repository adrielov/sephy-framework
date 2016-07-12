# Sephy-Simple-PHP-Framework
[![StyleCI](https://styleci.io/repos/60484041/shield)](https://styleci.io/repos/60484041)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sikamy/sephy-framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sikamy/sephy-framework/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/sikamy/sephy-framework/badges/build.png?b=master)](https://scrutinizer-ci.com/g/sikamy/sephy-framework/build-status/master)

A simple php framework using MVC structure and components of Symfony and Illuminate.

## Controllers & Views (Blade)
Blade is the simple, yet powerful templating engine provided with Laravel. Unlike other popular PHP templating engines, Blade does not restrict you from using plain PHP code in your views.
```
class HomeController extends Controller
{
	public function index() {
		$this->params['title'] = "Sephy Simple PHP Framework";
		$this->view('home.index',$this->params);
	}
}
```


## Routes
  Configure yours routes in App/config.php
```
  $router->add('/', 'HomeController::index');
  $router->get('/profile', 'UserController::profile');
  $router->get('/profile/{id}', 'UserController::profile',[
	'id' => '[0-9]'
  ]);

```
## Route Groups Prefix
The prefix group attribute may be used to prefix each route in the group with a given URI, like /dashboard/home
```
$router->prefix('dashboard', function (Core\Router $router) {
        $router->add('/home', 'DashboardController::index');
        $router->add('/config', 'DashboardController::config');
});
```
## Route Groups & Middleware
Middleware are filters to your routes, and often used for modifying or authenticating requests.
```
$router->group(['middleware' => ['auth']], function (Core\Router $router) {

    $router->add('/profile', 'UserController::profile');
    
});
```

## Authors

* **Adriel Oliveira** - [Sikamy](https://github.com/sikamy) - [Adriel OV](http://adrielov.com.br)
