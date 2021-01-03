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

    public function fetchPosts($category, $limit, $page, $title = null)
    {
        $post = $this->repository->fetchPosts($category, $limit, $page, $title);
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
                case '3':
                    array_push($animal, $post[$i]);
                    break;
                case '4':
                    array_push($plant, $post[$i]);
                    break;
                case '5':
                    array_push($insect, $post[$i]);
                    break;
            }
        }
        return ['animal' => $animal, 'plant' => $plant, 'insect' => $insect];
    }

    public function updatePost($id, $title, $author, $category, $description, $content, $userId) {
        $this->repository->updatePost($id, $title, $author, $category, $description, $content, $userId);
    }

    public function createPost($title, $author, $category, $description, $content, $userId) {
        $insertId = $this->repository->createPost($title, $author, $category, $description, $content, $userId);
        return $insertId;
    }

    public function deletePost($id) {
        $this->repository->deletePost($id);
    }
}
