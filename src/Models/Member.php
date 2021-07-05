<?php

declare(strict_types=1);

namespace Clicars\Models;

use Clicars\Interfaces\IMember;

class Member implements IMember
{
    private array   $subordinates;
    private IMember $actualBoss;
    private IMember $prisonBoss;


    public function __construct(private int $id, private int $age)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function addSubordinate(IMember $subordinate): IMember
    {
        $this->subordinates[] = $subordinate;

        return $this;
    }

    public function removeSubordinate(IMember $subordinate): ?IMember
    {
        foreach ($this->subordinates as $key => $value) {
            if ($value == $subordinate->getId()) {
                unset($this->subordinates[$key]);
                return $subordinate;
            }
        }

        return null;
    }

    public function removeAllSubordinates(): IMember
    {
        $this->subordinates = [];
        return $this;
    }

    public function getSubordinates(): array
    {
        return $this->subordinates ?? [];
    }

    public function getBoss(): ?IMember
    {
        return $this->actualBoss;
    }

    public function setBoss(?IMember $boss): IMember
    {
        if (!empty($boss)) {
            $this->actualBoss = $boss;
            $boss->addSubordinate($this);
        }

        return $this;
    }

    public function setPrisonBoss(IMember $prisonBoss): IMember
    {
        if(!empty($prisonBoss)){
            $this->prisonBoss = $prisonBoss;
        }

        return $this;
    }

    public function getPrisonBoss(): ?IMember
    {
        return $this->prisonBoss;
    }

    public function mergeSubordinates(array $subordinates): IMember
    {
        foreach ($subordinates as $subordinate){
            $subordinate->setPrisonBoss($subordinate->getBoss());
            $subordinate->setBoss($this);
        }

        return $this;
    }
}
