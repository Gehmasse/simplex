<?php

namespace Leo\Simplex;

class Simplex
{
    private int $width;

    public function __construct(
        private array $tab,
        private int $numberOfVariables,
        private bool $debug = false,
    )
    {
        $len = null;

        foreach($tab as $row) {
            $len2 = count($row);

            if($len !== null && $len !== $len2) {
                die('tableau not squared');
            }

            $len = $len2;
        }

        if($len === null) {
            die('len === null');
        }

        $this->width = $len;
    }

    public function run(): self
    {
        $k = 0;
        while(!$this->finished()) {
            $k++;

            if($k > 5) break;

            if($this->debug) echo $k, '. tableau:', PHP_EOL, PHP_EOL;

            if($this->debug) $this->print();

            [$i, $j] = $this->pivot();

            if($this->debug) echo 'PV (', $i, ', ', $j, ')', PHP_EOL, PHP_EOL;

            if($j === null) {
                die('j === null');
            }

            $this->simplex($i, $j);
        }

        if($this->debug) echo 'optimum:', PHP_EOL, PHP_EOL;

        if($this->debug) $this->print();

        return $this;
    }

    private function finished(): bool
    {
        return $this->z()[$this->pivotJ()] >= 0;
    }

    private function pivot(): array
    {
        $j = $this->pivotJ();

        $min = null;
        $iMin = null;

        foreach(array_slice($this->tab, 0, count($this->tab) - 1) as $i => $row) {
            $rightVal = $row[$this->width - 1];
            $pivElem = $row[$j];

            if($pivElem == 0) {
                if($this->debug) echo $i, ': ---', PHP_EOL;
                continue;
            }

            $quot = $rightVal / $pivElem;

            if($this->debug) echo $i, ': ', $quot, PHP_EOL;

            if($quot < 0) {
                continue;
            }

            if($min === null || $quot < $min) {
                $min = $quot;
                $iMin = $i;
            }
        }

        if($iMin === null) {
            die('iMin === null');
        }

        return [$iMin, $j];
    }

    private function pivotJ(): int
    {
        $min = $this->z()[0];
        $jMin = 0;

        foreach($this->z() as $j => $x) {
            if($x < $min) {
                $min = $x;
                $jMin = $j;
            }
        }
        
        return $jMin;
    }

    /** returns last row without rightmost value */
    private function z(): array
    {
        return array_slice($this->tab[count($this->tab) - 1], 0, $this->width - 1);
    }

    private function simplex(int $iPiv, int $jPiv): void
    {
        $piv = $this->tab[$iPiv][$jPiv];

        // bring pivot element to 1
        foreach($this->tab[$iPiv] as $j => $x) {
            $this->tab[$iPiv][$j] /= $piv;
        }

        $pivRow = $this->tab[$iPiv];

        // bring piv col to identity
        foreach($this->tab as $i => $row) {
            if($i === $iPiv) {
                continue;
            }

            $factor = $this->tab[$i][$jPiv];

            if($this->debug) echo $i, '. - ', $factor, ' * ', $iPiv, '.', PHP_EOL; 

            foreach($row as $j => $x) {
                $this->tab[$i][$j] = round($x - $factor * $pivRow[$j], 3);
            }
        }

        if($this->debug) echo PHP_EOL;
    }

    public function print(): self
    {
        foreach($this->tab as $row) {
            foreach($row as $x) {
                echo $x, "\t";
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;

        return $this;
    }

    public function solutions(): array
    {
        $solutions = [];

        for ($j = 0; $j < $this->width - 1 && $j < $this->numberOfVariables; $j++) {
            [$hasOne, $onePos] = $this->colIsIdentity($j);

            if($hasOne) {
                $res = $this->tab[$onePos][$this->width - 1];

                if($this->debug) echo 'x', $j, ' = ', $res, PHP_EOL;

                $solutions[] = $res;
            } else {
                if($this->debug) echo 'x', $j, ' = 0', PHP_EOL;

                $solutions[] = 0;
            }
        }

        return $solutions;
    }

    public function colIsIdentity(int $j): array
    {
        $hasOne = false;
        $onePos = null;

        foreach($this->tab as $i => $row) {
            if($row[$j] == 1) {
                if($hasOne) {
                    return [false, null];
                }

                $hasOne = true;
                $onePos = $i;
            } elseif($row[$j] == 0) {
                continue;
            } else {
                return [false,  null];
            }
        }

        return [$hasOne, $onePos];
    }
}
