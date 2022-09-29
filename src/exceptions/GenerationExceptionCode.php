<?php

namespace financas_api\exceptions;

class GenerationExceptionCode
{
    protected int $level = 0;
    protected int $subLevel = 0;
    protected int $class = 0;

    public function __construct(int $level, int $subLevel, int $class)
    {
        $this->level = $level;
        $this->subLevel = $subLevel;
        $this->class = $class;
    }

    public function generate(int $position)
    {
        $level = sprintf("%02s", $this->level);
        $subLevel = sprintf("%02s", $this->subLevel);
        $class = sprintf("%03s", $this->class);
        $position = sprintf("%03s", $position);

        return $level . $subLevel . $class . $position;
    }

}
    
?>