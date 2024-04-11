<?php

class Posts {

    private $error= "";

    public function create_post($userid, $data, $files) {
        if(!empty($data['post'])) {
            // Check if there is an uploaded file
            if (!empty($files['image']['name'])) {
                $targetDirectory = "uploads/";
                $targetFile = $targetDirectory . basename($files['image']['name']);
                
                // Move the uploaded file to the target directory
                if (move_uploaded_file($files['image']['tmp_name'], $targetFile)) {
                    $imagePath = $targetFile;
                    $hasImage = 1;
                } else {
                    $this->error .= "Failed to upload image. ";
                    return $this->error;
                }
            } else {
                $imagePath = ''; // No image uploaded
            }
    
            $post = addslashes($data['post']);
            $postid = $this->create_postid();
    
            $query = "INSERT INTO posts (post_id, user_id, post, image, has_image) VALUES ('$postid', '$userid', '$post', '$imagePath', '$hasImage')";
    
            $db = new Database();
            $db->save($query);
        }
        else {
            $this->error .= "Please type something to post!";
        }
    
        return $this->error;
    }
    

    public function get_posts($id){

        $query = "SELECT * FROM posts order by id desc";

        $db = new Database();
        $result = $db->read($query);

        if($result){
            return $result;
        }
        else {
            return false;
        }
    }

    private function create_postid(){
        $length = rand(4, 19);
        $number = "";
        for ($i=0; $i < $length; $i++){
            $new_rand = rand(0, 9);
            $number .= $new_rand;
        }
        return $number;
    }
}
?>