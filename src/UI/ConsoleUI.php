<?php

class ConsoleUI {
    function showMessage($selection) {
        if (intval($selection) != 1 && intval($selection) != 2) {
            print("Invalid Selection: $selection \n");
        } else if ($selection == 1) {
            print("Selected Strategy: Smart \n");
            return 0;
        } else {
            print("Selected Strategy: Random \n");
            return 1;
        }
        return 2;
    }

    function promptServer($defaults) {
        $url = readline('Enter the server URL [default: https://cssrvlab01.utep.edu/Classes/cs3360/rrsillas/info] ');
        print("Obtaining server information ......");
        if($url == "") {
            $url = $defaults;
        }
        return $url;
    }

    function promptStrategy($info) {
        $smart = $info[0];
        $random = $info[1];
        $pick = readline("Select the server strategy: 1. $smart 2. $random [default: 1] \n");
        if($pick == "") {
            return 1;
        }
        return intval($pick);
    }

    function promptMove() {
        return readline("Select a slot [1-7]: ");
    }
}
