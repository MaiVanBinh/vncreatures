<?php

namespace App\Domain\Posts;

use App\Domain\Posts\PostsRepository;

class PostsServices
{
    private $repository;

    public function __construct(PostsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchPostById($id)
    {
        $post = $this->repository->fetchPostById($id);
        return $post;
    }

    public function fetchPosts($category, $limit, $page)
    {
        $post = $this->repository->fetchPosts($category, $limit, $page);
        return $post;
    }

    public function fetchPostIndentify()
    {
        $post = $this->repository->fetchPostIndentify();
        $animal = [];
        $plant = [];
        $insect = [];
        for ($i = 1; $i < count($post); $i++) {
            switch ($post[$i]['category']) {
                case 'identify_animal':
                    array_push($animal, $post[$i]);
                    break;
                case 'identify_plant':
                    array_push($plant, $post[$i]);
                    break;
                case 'identify_insect':
                    array_push($insect, $post[$i]);
                    break;
            }
        }
        return ['animal' => $animal, 'plant' => $plant, 'insect' => $insect];
    }
}
