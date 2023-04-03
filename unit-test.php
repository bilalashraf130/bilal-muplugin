<?php

require "vendor/autoload.php";


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // Create a new dotenv instance
$dotenv->load();
$wordpress_path = env("WORDPRESS_PATH","/");

require $wordpress_path."/wp-blog-header.php";
