<?php

declare(strict_types=1);
class Area
{
    private int $code;
    private string $nameArea;
    private string $descripction;
    private string $img;
    private int $status;
    public function __construct()
    {
    }

    public function getDescription(): string
    {
        return $this->descripction;
    }

    public function setDescription(string $description): void
    {
        $this->descripction = $description;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getNameArea(): string
    {
        return $this->nameArea;
    }

    public function setNameArea(string $nameArea): void
    {
        $this->nameArea = $nameArea;
    }

    public function getImg(): string
    {
        return $this->img;
    }

    public function setImg(string $img): void
    {
        $this->img = $img;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
