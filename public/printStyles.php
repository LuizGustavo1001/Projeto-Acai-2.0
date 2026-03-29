<?php 
    // function to print the style file and the version at the correct page 
    // everytime that you change something at the styles you are supose to change the version too at $version
    function printStyle($fileName){
        $version = "?v=3.23";

        $directory = "/css/";

        $cssFile = match($fileName){
            "universal"         => "universal.css",
            "general"           => "general.css",
            "account"           => "account.css",
            "cart"              => "cart.css",
            "index"             => "index.css",
            "mannager"          => "mannager.css",
            "mannagerSettings"  => "mannager-settings.css",
            "products"          => "products.css",
            "productVersion"    => "productView.css",
            default             => "general.css",
        };

        echo "{$directory}{$cssFile}{$version}";
    }