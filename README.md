[![Coverage Status](https://coveralls.io/repos/github/sikamy/sephy-framework/badge.svg?branch=master)](https://coveralls.io/github/sikamy/sephy-framework?branch=master)
[![StyleCI](https://styleci.io/repos/60484041/shield)](https://styleci.io/repos/60484041)

# Sephy-Simple-PHP-Framework
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
  $router->post('/profile/{id}', 'UserController::profile');
```
## Route Groups Prefix
The prefix group attribute may be used to prefix each route in the group with a given URI. 
```
$router->prefix('dashboard', function (Core\Router $router) {
    $router->group(['middleware' => ['auth']],function (Core\Router $router) {
    
        $router->add('/home', 'DashboardController::index');
        
    });
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
