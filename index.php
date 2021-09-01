<?php

require 'src/Robot.php';

if($argc != 2){
    echo "Kindly Provide instructions to the Robot! \n";
    echo "----------------------------------------\n";
    echo "R: Turn 90 degree to the right of MAQE Bot (clockwise) \n";
    echo "L: Turn 90 degree to the left of MAQE Bot (counterclockwise) \n";
    echo "WN: Walk straight for N point(s) where N can be any positive integers.For example, W1 means walking straight for 1 point. \n";
    echo "----------------------------------------\n";
    echo "MAQE Bot starts at 0, 0 facing up North. \n";
    echo "MAQE Bot turns right (facing East) and walk straight 15 positions. \n";
    echo "MAQE Bot turns another right (now facing South) and walk straight 1 position. \n";

    exit;
}

$robot = new Robot($argv[1]);
$robot->handle();
