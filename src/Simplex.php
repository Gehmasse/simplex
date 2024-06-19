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

            $this->debug($k . '. tableau:');

            if($this->debug) $this->print();

            [$i, $j] = $this->pivot();

            $this->debug('PV ('. $i. ', '. $j . ')');

            if($j === null) {
                die('j === null');
            }

            $this->simplex($i, $j);
        }

        $this->debug('optimum:');

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
                $this->debug($i . ': ---', 1);
                continue;
            }

            $quot = $rightVal / $pivElem;

            $this->debug($i . ': ' . $quot, 1);

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

            $this->debug($i . '. - ' . $factor . ' * ' . $iPiv . '.', 1);

            foreach($row as $j => $x) {
                $this->tab[$i][$j] = round($x - $factor * $pivRow[$j], 3);
            }
        }

        $this->debug();
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

                $this->debug('x' . $j . ' = ' . $res, 1);

                $solutions[] = $res;
            } else {
                $this->debug('x' . $j . ' = 0', 1);

                $solutions[] = 0;
            }
        }

        $this->debug();

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

    private function debug(string $s = '', int $breaks = 2): void
    {
        if($this->debug) {
            echo $s . str_repeat(PHP_EOL, $breaks);
        }
    }
}
