<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Routing\Router;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoutingTest extends TestCase
{

    private function testSomething(){

        require ABSPATH . WPINC . '/pluggable.php';


    }

    public function testGetRoutes(){

        $this->testSomething();

        $routes = $this->app->make("router")->getRoutes();
        foreach ($routes as $route) {
           $method = $route["method"];
           $uri = $route["uri"];
           $action = $route["action"];
           if($method==="GET"){

               if(isset($action["wordpress"])){
                   $this->testWordpressRoute($uri);
               }else{
                   $this->testLumenRoutes($uri);
               }

           }

            // Add additional checks here, such as checking the response content.
        }
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    private function testWordpressRoute($url)
    {
        $response = Http::get('https://athletes-ocean.lndo.site/'.$url);
        $this->assertEquals(200,$response->status(),"Route Status Testing for ".$url);



        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML($response);
        libxml_use_internal_errors(false);
            //CHECKING THE LANDING PAGE

        $element = $dom->getElementById('mastheads');

        $this->assertIsObject($element);

    }
    /**
     * A basic test example.
     *
     * @return void
     */
    private function testLumenRoutes($url)
    {
        $this->get($url)->assertResponseStatus(200);


    }

    /**
     * A basic test example.
     *
     * @return void
     */
    private function testNotFoundRoutes()
    {
        $this->get("/somerandomroute")->assertResponseStatus(404);


    }

}
