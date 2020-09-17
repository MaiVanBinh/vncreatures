<?php
namespace App\Domain\Groups;

use App\Domain\Groups\GroupsRepository;

class GroupsServices {
    /**
     * @GroupsRepository
     */
    private $repository;

    /**
     * Constructer
     * 
     * @param GroupsRepository
     * @return Void
     */
    public function __construct(GroupsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchGroup() {
        $groups = $this->repository->fetchGroup();
        return $groups;
    }

    public function getGroupsBySpecies($loaiId) {
        $Groups = $this->repository->GroupsListBySpecies($loaiId);
        return $Groups;
    }
    
}