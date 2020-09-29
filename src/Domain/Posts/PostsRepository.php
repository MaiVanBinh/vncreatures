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
            $sql = "SELECT title, content FROM vncreatures.posts WHERE id=:postId;";
            $db = $this->connection->prepare($sql);
            $db->execute(['postId' => $id]);
            $post = $db->fetchAll();
            if (count($post) === 0) {
                throw new Exception('Not Found');
            }
            // $sql = "UPDATE vncreatures.posts SET content='{$post[0]['content']}' WHERE id=:postId;";
            // $db = $this->connection->prepare($sql);
            // $db->execute(['postId' => $id]);
            return $post[0];
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }
    }

    public function fetchPosts($category, $limitPost, $page)
    {
        $offset = ($page - 1) * $limitPost;
        if ($category != '') {
            $sql = "SELECT id, title, category, created_at, description FROM posts where category = {$category} LIMIT {$limitPost} OFFSET {$offset}";
        } else {
            $sql = "SELECT id, title, category, created_at, description FROM posts LIMIT {$limitPost} OFFSET {$offset}";
        }
        
        $db = $this->connection->prepare($sql);
        $db->execute();
        $posts = $db->fetchAll();
        if($category === '6' || $category === '7' || $category === '8') {
            return $posts;
        }
        $postsUpdate = [];
        for ($i = 0; $i < count($posts); $i++) {
            $sql = "SELECT a.url from assets a, assets_posts ap  where a.id = ap.asset and ap.post = {$posts[$i]['id']} order by a.id limit 1;";
            $db = $this->connection->prepare($sql);
            $db->execute();
            $images = $db->fetchAll();

            if (count($images) > 0) {
                $post[$i]['image'] = $images[0]['url'];
                array_push($postsUpdate, [
                    'image' => $images[0]['url'],
                    'id' => $posts[$i]['id'],
                    'title' => $posts[$i]['title'],
                    'category' => $posts[$i]['category'],
                    'created_at' => $posts[$i]['created_at'],
                    'description' => $posts[$i]['description']
                ]);
            } else {

            }
        }
        return $postsUpdate;
        return ['sql' => $sql];
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
        $sql = "SELECT id, title, category FROM posts where category=:category LIMIT :limitPost OFFSET :offset";
        $db = $this->connection->prepare($sql);
        $db->execute(['limitPost' => $limitPost, 'offset' => $offset, 'category' => $category]);
        $posts = $db->fetchAll();
        return $posts;
    }
}
