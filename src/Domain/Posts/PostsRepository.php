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
            $sql = "SELECT * FROM vncreatu_vncreature_new.posts WHERE id=:postId;";
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

    public function fetchPosts($category, $limitPost = 10, $page = 1, $title = null)
    {
        $offset = ($page - 1) * $limitPost;
        $sql = "SELECT * FROM posts order by id LIMIT :limitPost OFFSET :offset";
        $sqlCount = "SELECT count(id) as total FROM posts;";
        if ($title) {
            $sql = "SELECT * FROM posts where title like '%{$title}%' order by id LIMIT :limitPost OFFSET :offset";
            $sqlCount = "SELECT count(id) as total FROM posts where title like '%{$title}%';";
        }
        if ($category) {
            $sql = "SELECT * FROM posts where category={$category} order by id LIMIT :limitPost OFFSET :offset";
            $sqlCount = "SELECT count(id) as total FROM posts;";
        }
        $db = $this->connection->prepare($sql);
        $dbCount = $this->connection->prepare($sqlCount);
        $dbCount->execute();
        $total = $dbCount->fetchAll()[0]['total'];
        $db->bindParam(':limitPost', $limitPost, PDO::PARAM_INT);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        $db->execute();
        $posts = $db->fetchAll();


        $postsUpdate = [];
        for ($i = 0; $i < count($posts); $i++) {
            $id = (int) $posts[$i]['id'];
            $sql = "SELECT a.url from assets a, assets_posts ap  where a.id = ap.asset and ap.post =:id order by a.id limit 1;";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->execute();
            $images = $db->fetchAll();
            if (count($images) > 0) {
                $post[$i]['image'] = $images[0]['url'];
                array_push($postsUpdate, [
                    'image' => $images[0]['url'],
                    'id' => $posts[$i]['id'],
                    'title' => $posts[$i]['title'],
                    'author' => $posts[$i]['author'],
                    'content' => $posts[$i]['content'],
                    'category' => $posts[$i]['category'],
                    'created_at' => $posts[$i]['created_at'],
                    'updated_at' => $posts[$i]['updated_at'],
                    'created_by' => $posts[$i]['created_by'],
                    'description' => $posts[$i]['description']
                ]);
            } else {
                array_push($postsUpdate, $posts[$i]);
            }
        }
        return ['total' => $total, 'posts' => $postsUpdate];

        return ['total' => $total, 'posts' => $posts];


        // $db = null;
        // if ($category) {
        //     $sql = "SELECT * FROM posts where category =:category order by id LIMIT :limitPost OFFSET :offset;";
        //     $db = $this->connection->prepare($sql);
        //     $db->bindParam(':category', $category, PDO::PARAM_INT);

        //     $sqlCount = "SELECT count(id) as total FROM posts where category =:category;";
        //     $dbCount = $this->connection->prepare($sqlCount);
        //     $dbCount->bindParam(':category', $category, PDO::PARAM_INT);
        // } else {
        //     // $sql = "SELECT * FROM posts where category in (1,2) order by id LIMIT :limitPost OFFSET :offset";
        //     $sql = "SELECT * FROM posts order by id LIMIT :limitPost OFFSET :offset";
        //     $sqlCount = "SELECT count(id) as total FROM posts;";
        //     $db = $this->connection->prepare($sql);
        //     $dbCount = $this->connection->prepare($sqlCount);
        // }
        // $dbCount->execute();
        // $total = $dbCount->fetchAll()[0]['total'];
        // $db->bindParam(':limitPost', $limitPost, PDO::PARAM_INT);
        // $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        // $db->execute();
        // $posts = $db->fetchAll();
        // // 
        // // return ['posts' => $posts, 'total' => $total];
        // // if ($category === '6' || $category === '7' || $category === '8') {

        // // }

        // return ['sql' => $sql];
    }

    public function fetchPostIndentify()
    {
        $sql = "SELECT id, title, category FROM vncreatu_vncreature_new.posts where category in (3,4,5);";
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

    public function updatePost($id, $title, $author, $category, $description, $content, $userId)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE vncreatu_vncreature_new.posts 
            SET 
                title=:title, 
                author=:author, 
                category=:category, 
                description=:description, 
                content=:content, 
                updated_at='{$date}',
                updated_by=:updated_by
                 WHERE id=:id";

        $db = $this->connection->prepare($sql);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':author', $author, PDO::PARAM_STR);
        $db->bindParam(':category', $category, PDO::PARAM_INT);
        $db->bindParam(':description', $description, PDO::PARAM_STR);
        $db->bindParam(':content', $content, PDO::PARAM_STR);
        $db->bindParam(':updated_by', $userId, PDO::PARAM_INT);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }

    public function createPost($title, $author, $category, $description, $content, $userId)
    {
        $sql = "INSERT 
            vncreatu_vncreature_new.posts (title, author, category, description, content, created_by, updated_by) 
        values (:title, :author, :category, :description, :content, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':author', $author, PDO::PARAM_STR);
        $db->bindParam(':category', $category, PDO::PARAM_INT);
        $db->bindParam(':description', $description, PDO::PARAM_STR);
        $db->bindParam(':content', $content, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->execute();
        return (int)$this->connection->lastInsertId();
    }

    public function deletePost($id)
    {
        $sql = "DELETE FROM posts WHERE id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
}
