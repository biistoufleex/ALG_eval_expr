<?php

class Eval_expr
{
    public $expr  = [];
    public $stack = [];
    public $npi   = [];
    public $value = [
        '(' => 0,
        '+' => 1,
        '-' => 1,
        '*' => 2,
        '/' => 2,
        '%' => 2,
    ];
    public $left   = 0;
    public $right  = 0;
    public $result = 0;

    function __construct($expr)
    {

        preg_match_all('/(\(|\d*(?:\.\d+)?|-|\+|\/|\*|\%|\))/', $expr, $output);
        $this->expr = $output[0];
        print_r($this->expr);
        $this->createNpi();
        $this->solveNpi();
    }

    function createNpi()
    {
        while (!empty($this->expr)) {

            if ($this->expr[0] === "") {
                array_shift($this->expr);
            } else {

                if (is_numeric($this->expr[0])) {
                    array_push($this->npi, array_shift($this->expr));
                } else {

                    if (empty($this->stack) || $this->expr[0] == "(") {
                        array_push($this->stack, array_shift($this->expr));
                    } else {

                        if ($this->expr[0] === ")") {
                            while (end($this->stack) != "(") {
                                array_push($this->npi, array_pop($this->stack));
                            }
                            array_splice($this->expr, 0, 1);
                            array_splice($this->stack, count($this->stack) - 1, 1);
                        } else {

                            if ($this->value[$this->expr[0]] > $this->value[end($this->stack)]) {
                                array_push($this->stack, array_shift($this->expr));
                            } else {

                                while ($this->value[end($this->stack)] >= $this->value[$this->expr[0]]) {
                                    array_push($this->npi, array_pop($this->stack));
                                    if (empty($this->stack)) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        while (!empty($this->stack)) {
            array_push($this->npi, array_pop($this->stack));
        }
    }

    function solveNpi()
    {
        $this->expr = $this->npi;
        echo implode($this->expr);
        $this->npi = [];

        while (!empty($this->expr)) {

            if (is_numeric($this->expr[0])) {
                array_push($this->stack, array_shift($this->expr));
            } else {

                $this->right = array_pop($this->stack);
                $this->left = array_pop($this->stack);

                switch ($this->expr[0]) {
                    case '+':
                        $this->result = $this->left + $this->right;
                        break;

                    case '-':
                        $this->result = $this->left - $this->right;
                        break;

                    case '*':
                        $this->result = $this->left * $this->right;
                        break;

                    case '/':
                        $this->result = $this->left / $this->right;
                        break;

                    case '%':
                        $this->result = $this->left % $this->right;
                        break;

                    default:
                        break;
                }
                array_push($this->stack, $this->result);
                array_shift($this->expr);
            }
        }
        $this->result = array_pop($this->stack);
        echo "Resultat: " . $this->result . "\n";
    }
}

// $alg = new Eval_expr('((3*5-4*7)/1+1)-1/8*8+3');
