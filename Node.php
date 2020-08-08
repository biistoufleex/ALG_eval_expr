<?php 

class Node {

    public $value;
    public $left = new Node($expr);
    public $right = new Node($expr);

    function __construct($expr) {
        
    }
}


$eval = new Node($expr);


// donner des priotier au signe