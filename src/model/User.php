<?php
declare(strict_types=1);
Class User{
    private int $id;
    private string $name;
    private string $fullName;
    private string $pwd;
    private int $status;

    public function __construct() {
      }

    public function getPwd(): string{
        return $this->pwd;
    }
    public function setPwd(string $pwd): void{
        $this->pwd = $pwd;

    }


    public function getFullName(): string{
        return $this->fullName;
    }
    public function setFullName(string $fullName): void{
        $this->fullName = $fullName;
    }
      public function getId(): int {
        return $this->id;
    }
    
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }
    
    public function setName(string $name): void {
        $this->name = $name;
    }
    public function getStatus(): int {
        return $this->status;
    }
    
    public function setStatus(int $status): void {
        $this->status = $status;
    }

}
?>