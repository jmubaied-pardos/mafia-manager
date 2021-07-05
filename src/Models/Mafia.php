<?php

declare(strict_types=1);

namespace Clicars\Models;

use Clicars\Interfaces\IMafia;
use Clicars\Interfaces\IMember;

class Mafia implements IMafia
{
    private array   $members;
    private array   $prison;

    public function __construct(private IMember $godfather)
    {
    }

    public function getGodfather(): IMember
    {
        return $this->godfather;
    }

    public function addMember(IMember $member): ?IMember
    {
        $this->members[] = $member;

        return $member;
    }

    public function getMember(int $id): ?IMember
    {
        foreach ($this->members as $member) {
            if ($member->getId() == $id) {
                return $member;
            }
        }

        return null;
    }

    public function sendToPrison(IMember $member): bool
    {
        foreach ($this->members as $key => $value) {
            if ($value->getId() == $member->getId()) {
                unset($this->members[$key]);
                if (!$this->isMemberInPrison($member)) {
                    $this->prison[] = $member;
                    $this->relocateSubordinates($member);
                }

                return true;
            }
        }

        return false;
    }

    public function releaseFromPrison(IMember $member): bool
    {
        foreach ($this->prison as $key => $value) {
            if ($value->id == $member->getId()) {
                unset($this->prison[$key]);
                if (!$this->isInMembers($member)) {
                    $this->members[] = $member;
                    $this->recoverSubordinates($member);
                }

                return true;
            }
        }

        return false;
    }

    public function findBigBosses(int $minimumSubordinates): array
    {
        $bigBosses = [];

        if(!empty($this->members)){
            foreach ($this->members as $member) {
                $subordinates = $member->getSubordinates();
                if (count($subordinates) >= $minimumSubordinates) {
                    $bigBosses[] = $member;
                }
            }
        }

        return $bigBosses;
    }

    public function compareMembers(IMember $memberA, IMember $memberB): ?IMember
    {
        if ($memberA->getAge() < $memberB->getAge()) {
            return $memberB;
        }elseif ($memberA->getAge() > $memberB->getAge()){
            return $memberA;
        }

        return null;
    }

    private function isMemberInPrison(IMember $member): bool
    {
        if(!empty($this->prison)){
            foreach ($this->prison as $prisoner) {
                if ($prisoner->getId() == $member->getId()) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isInMembers(IMember $member): bool
    {
        foreach ($this->members as $item) {
            if ($item->getId() == $member->getId()) {
                return true;
            }
        }

        return false;
    }

    private function relocateSubordinates(IMember $member): void
    {

        $newBoss = $this->findProperBoss($member)->mergeSubordinates($member->getSubordinates());
        $member->removeAllSubordinates();
    }

    private function recoverSubordinates(IMember $member): void
    {
        //TODO: Find subordinates with same boss and equal to a param member
    }

    private function findProperBoss(IMember $member): IMember
    {
        $boss = $member->getBoss();

        $possibleBosses = [];

        foreach ($this->members as $item) {
            if ($item->getBoss()->getId() == $boss->getId()) {
                $possibleBosses[] = $item;
            }
        }


        $newBoss = $possibleBosses[0];
        foreach ($possibleBosses as $possibleBoss) {
            if ($possibleBoss->getAge() > $newBoss->getAge()) {
                $newBoss = $possibleBoss;
            }
        }

        return $newBoss;
    }
}
