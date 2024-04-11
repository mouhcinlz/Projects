<?php
    session_start();
    include("classes/connect.php");
    include("classes/log-in.php");
    include("classes/users_data.php");
    include("classes/posts.php");

    // Check if the user is logged in
    if (isset($_SESSION['users_id']) && is_numeric($_SESSION['users_id'])) {

        $id = $_SESSION['users_id'];
        $login = new login();
        $result = $login->check_login($id);

        if (is_array($result) && array_key_exists('error', $result)) {
            // Handle the error case
            echo "Error: " . $result['error'];
        } elseif ($result) {
            $type = $result['type'];

            // Redirect based on user type
            if ($type === 'farmer') {
                header("Location: farmer.php");
                die;
            } elseif ($type === 'veterinary') {

                $users = new users_data();
                $users_d = $users -> get_vet_data($id);

                if(!$users_d){
                    header("Location: log-in.php");
                    die;
                }
            } else {
                // Handle other user types or show an error
                echo "Unknown user type!";
            }
        } else {
            header("Location: log-in.php");
            die;
        }
    } else {
        header("Location: log-in.php");
        die;
    }
    $error_msg = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $post = new Posts();
        $id = $_SESSION['users_id'];
        // Pass $_FILES along with $_POST to create_post method
        $result = $post->create_post($id, $_POST, $_FILES);

        if($result == ""){
            header("Location: veterinary.php");
            die;
        }
        else {
            $error_msg = $result;
        }

    }

    //collect posts
    $post = new Posts();
    $id = $_SESSION['users_id'];
    $posts = $post->get_posts($id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Veterinary</title>
    <link rel="stylesheet" href="css/all.min.css" />

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/logout.css">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;500&#038;display=swap" rel="stylesheet" />
</head>
<body>
    <div class="page ">
        
        <div class="sidebar">
            <div class="fix-sidebar">
                <h3 class="title">FarmVet</h3>
                <ul>
                    <li>
                    <a class="active" href="veterinary.php">
                        <i class="fa-solid fa-house fa-fw"></i>
                        <span>Home</span>
                    </a>
                    </li>
                    <li>
                    <a class="" href="veterinaryProfile.php">
                        <i class="fa-regular fa-user fa-fw"></i>
                        <span>Profile</span>
                    </a>
                    </li>
                    <li>
                    <a class="" href="messages.html">
                        <i class="fa-solid fa-comments fa-fw"></i>
                        <span>Messages</span>
                    </a>
                    </li>
                    <li>
                    <a class="" href="store.html">
                        <i class="fa-solid fa-comments fa-fw"></i>
                        <span>Store</span>
                    </a>
                    </li>
                    <li>
                    <a class="" href="contact.html">
                        <i class="fa-solid fa-envelope fa-fw"></i>
                        <span>Contact Us</span>
                    </a>
                    </li>
                </ul>
                <div class="box">
                    <?php
                        $image = "imgs/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg";
                        if(file_exists($users_d['image'])){
                            $image = $users_d['image'];
                        }
                    ?>
                    <img src="<?php echo $image; ?>" alt="">
                    <div class="info">
                        <span>@<?php echo $users_d['firstn'] . $users_d['lastn'] ?>
                            <br>
                            <p>Veterinary</p>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="content">

        <div class="box1">
            
            <div class="head-box">
                <div class="title">
                    <a href="veterinary.php" class="active">For You</a>
                    <a href="contact.php"> Contact Us</a>
                    <span class="notification">
                        <i class="fa-regular fa-bell fa-lg"></i>
                    </span>
                </div>
            </div>
            <div class="body-box">
                <div class="post">
                <form method="post" enctype="multipart/form-data">
                        <div class="text">
                            <img src="<?php echo $image; ?>" alt="">
                            <div class="input-field">
                                <textarea name="post" cols="1" rows="1" placeholder="What's new"></textarea>
                            </div>
                        </div>
                        <div class="posting">
                            <div class="icons">
                                <div class="input-div">
                                    <input name="image" type="file">
                                </div>
                                <i class="fa-solid fa-face-smile-beam"></i>
                            </div>
                            <?php
                                echo "<div style='color: red;font-size: 12px;margin: 0 0 5px 0'>";
                                echo $error_msg ;
                                echo "</div>";
                            ?>
                            <div class="button">
                                <input type="submit" value="Share">
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                    if($posts){

                        foreach ($posts as $row){

                            $users = new users_data();
                            $row_users = $users->get_info_post($row['user_id']);
                            $row_farmer = $users->get_farmer_data($row['user_id']);
                            $row_vet = $users->get_vet_data($row['user_id']);

                            include("post.php");
                        }
                    }
                    
                ?>

            </div>
        </div>

        <div class="box2">
            <div class="head-box">
                <div class="search ">
                    <input class="p-10" type="search" placeholder="Type A Keyword" />
                    <button class="Btn">
                        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
                        <a href="logout.php" class="text" >Logout</a>
                    </button>
                </div>
            </div>

        </div>
        
        </div>
    </div>
    

</body>
</html>