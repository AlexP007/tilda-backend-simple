<?php

class TriangleNumLinesIterator implements IteratorAggregate
{
    private int $maxPosition;

    public function __construct(
        public readonly int $max, 
        public readonly bool $strict,
    ) {
        $this->calcMaxPosition();
    }

    public function getIterator(): Traversable
    {
        $i = 1;
        $n = 0;

        while ($i <= $this->maxPosition) {
            $n += $i;
            yield $this->line($i, $n - $i + 1, $n);         
            $i++;
        }

        if ($this->strict && $n < $this->max) {
            yield $this->line($this->max - $n, $n + 1, $this->max);
        }        
    }
    
    private function line(int $repeat, int $min, int $max): string
    {
        return vsprintf(
            ltrim(str_repeat('%d ', $repeat)),
            range($min, $max),
        );
    }

    private function calcMaxPosition(): void
    {
        $p = (-1 + sqrt(1 + 4 * 2 * $this->max)) / 2;

        $this->maxPosition = floor($p);
    }
}

class LineEcho
{
    public function __construct(
        public readonly string $separator,
    ) {}

    public function print(IteratorAggregate $iterator): void
    {
        foreach ($iterator as $str) {
            echo $str . $this->separator;
        }
    }
}

$echoer = new LineEcho(PHP_EOL);

$echoer->print(
    new TriangleNumLinesIterator($argv[1] ?? 100, true),
);

