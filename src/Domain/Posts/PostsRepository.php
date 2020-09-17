<?php

namespace App\Domain\Posts;

use Exception;
use PDO;

class PostsRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchPostById($id)
    {
        try {
            $sql = "SELECT * FROM vncreatures.posts WHERE id=:postId;";
            $db = $this->connection->prepare($sql);
            $db->execute(['postId' => $id]);
            $post = $db->fetchAll();
            if (count($post) === 0) {
                throw new Exception('Not Found');
            }
            $sql = "UPDATE vncreatures.posts SET content='{$post[0]['content']}' WHERE id=:postId;";
            $db = $this->connection->prepare($sql);
            $db->execute(['postId' => $id]);
            return $post[0];
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }
    }

    public function fetchPosts($category, $limitPost, $page)
    {
        $offset = ($page -1) * $limitPost;
        if($category != '') {
            // $category = html_entity_decode($category, ENT_NOQUOTES, 'UTF-8');
            $sql = "SELECT id, title FROM posts where category like '{$category}' LIMIT {$limitPost} OFFSET {$offset}";
        } else {
            $sql = "SELECT id, title FROM posts LIMIT {$limitPost} OFFSET {$offset}";
        }
        
        $db = $this->connection->prepare($sql);
        $db->execute();
        $posts = $db->fetchAll();
        $postsUpdate = [];
        for($i = 0; $i < count($posts); $i++) {
            $sql = "SELECT a.url from assets a, assets_posts ap  where a.id = ap.asset and ap.post = {$posts[$i]['id']} order by a.id limit 1;";
            $db = $this->connection->prepare($sql);
            $db->execute();
            $images = $db->fetchAll();
            $post[$i]['image'] = $images[0]['url'];
            array_push($postsUpdate, ['image' => $images[0]['url'], 'id' => $posts[$i]['id'], 'title' => $posts[$i]['title']]);
        }
        return $postsUpdate;
        // return ['sql' => $sql];
    }

    public function fetchPostIndentify()
    {
        $sql = "SELECT id, title, category FROM vncreatures.posts where category like 'identify%';";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $posts = $db->fetchAll();
        return $posts;
    }

    // Fetch post by catagory, limit, page
    public function fetchPostByCategory($category, $limit, $page)
    {

        $limitPost = $limit ? $limit : 5;
        $page = $page ? $page : 5;
        $offset = $page * $limitPost;
        $sql = "SELECT id, title FROM posts where category=:category LIMIT :limitPost OFFSET :offset";
        $db = $this->connection->prepare($sql);
        $db->execute(['limitPost' => $limitPost, 'offset' => $offset, 'category' => $category]);
        $posts = $db->fetchAll();
        return $posts;
    }
}
