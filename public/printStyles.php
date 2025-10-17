<?php 
    // function to print the style file and the version at the correct page 
    // everytime that you change something at the styles you are supose to change the version too at $version
    function printStyle($local, $fileName){
        $version = "?v=2.9";

        $directory = match($local){
            "1" => "../CSS/",
            "2" => "../../CSS/",
            default => "CSS/",
        };

        $cssFile = match($fileName){
            "general"           => "general-styles.css",
            "account"           => "account.css",
            "cart"              => "cart.css",
            "index"             => "index.css",
            "mannager"          => "mannager.css",
            "mannagerSettings"  => "mannager-settings.css",
            "products"          => "products.css",
            "productVersion"    => "productView.css",
            default             => "general-styles.css",
        };

        echo "{$directory}{$cssFile}{$version}";
    }
