<?php

require_once 'vendor/autoload.php';

// maximise z = 300 * x1 + 200 * x2 - 50000
// under the following constraints:
//   I) x1 + 2 * x2 + y1 = 1000
//  II) 25 * x1 + 10 * x2 + y2 = 10000
// III) x1 + y3 = 700
//  IV) x2 + y4 = 500
//   V) x, y >= 0 (non-negativity constraint)

// Build the Simplex-Tableau and insert as a two-dimensional array in below.
// As a second parameter to the constructor, pass the number of variables that have to be evaluated in the solution (e. g. 2 for only x and 6 for also y).
// The third parameter is an optional boolean that indicated is the solution should be shon step by step.

echo json_encode((new Leo\Simplex\Simplex([
    [ 1,  2, 1, 0, 0, 0,  1000], // constraint 1
    [25, 10, 0, 1, 0, 0, 10000], // constraint 2
    [ 1,  0, 0, 0, 1, 0,   700], // constraint 3
    [ 0,  1, 0, 0, 0, 1,   500], // constraint 4
    
    [-300, -200, 0, 0, 0, 1, -50000], // function to maximise
], 3))->run()->solutions()), PHP_EOL;
