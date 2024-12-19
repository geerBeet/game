<?php
namespace Ilya\Game;
class Player
{
    private string $name;
    private $position;

    public function __construct($name)
    {
        $this->name = $name;
        $this->position = 0;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}