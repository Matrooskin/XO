<?php

namespace XO\Player;

/**
 * This class provides not very smart player ;)
 */
class DariusKPlayer implements PlayerInterface
{
    protected $table;

    protected $turn;

    protected $opponentSymbol;

    /**
     * @inheritdoc
     */
    public function turn($table, $symbol = self::SYMBOL_X)
    {
        $this->table = $table;
        if ($symbol === self::SYMBOL_X) {
            $this->opponentSymbol = self::SYMBOL_O;
        } else {
            $this->opponentSymbol = self::SYMBOL_X;
        }

        //Always start from middle
        $x = $y = 1;
        $this->doTurn($x, $y);

        if ($this->turn !== null) {
            return $this->turn;
        }
        //Check if is two same symbol in line
        $y = $this->getDangerLine();
        if ($y !== null) {
            $x = $this->getEmptyColumn($y);
            $this->doTurn($x, $y);
        }

        if ($this->turn !== null) {
            return $this->turn;
        }
        //Check if is two same symbol in row
        $x = $this->getDangerColumn();
        if ($x !== null) {
            $y = $this->getEmptyLine($x);
            $this->doTurn($x, $y);
        }

        foreach($this->table as $y => $row) {
            foreach($this->table as $x => $symbol) {
                $this->doTurn($x, $y);
                if ($this->turn !== null) {
                    return $this->turn;
                }
            }
        }

        return $this->turn;
    }

    protected function getEmptyLine($x)
    {
        $y = 0;
        foreach($this->table as $key => $row) {
            if (empty($row[$x])) {
                $y = $key;
            }
        }

        return $y;
    }

    protected function getEmptyColumn($line)
    {
        $x = 0;
        foreach ($this->table[$line] as $key => $row) {
            if (empty($row)) {
                $x = $key;
            }
        }
        return $x;
    }

    protected function getDangerColumn()
    {
        $rows = [0, 0, 0];
        foreach ($this->table as $line) {
            foreach ($line as $x => $symbol) {
                if ($symbol === $this->opponentSymbol) {
                    $rows[$x]++;
                }
            }
        }
        foreach ($rows as $key => $row) {
            if ($row == 2) {
                return $key;
            }
        }
        return false;
    }

    protected function getDangerLine()
    {
        foreach ($this->table as $key => $line) {
            if (count(array_filter($line, [$this, 'isSymbol'])) == 2) {
                return $key;
            }
        }
        return false;
    }

    public function isSymbol($symbol)
    {
        return $symbol === $this->opponentSymbol;
    }

    protected function doTurn($x, $y)
    {
        if ($this->isEmpty($x, $y)) {
            $this->turn = [$x, $y];
        }
    }

    protected function isEmpty($x, $y)
    {
        return empty($this->table[$x][$y]);
    }
}
