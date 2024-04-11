<?php
    session_start();
    include("classes/connect.php");
    include("classes/log-in.php");
    include("classes/users_data.php");


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

                $users = new users_data();
                $users_d = $users -> get_farmer_data($id);

                if(!$users_d){
                    header("Location: log-in.php");
                    die;
                }

                
            } elseif ($type === 'veterinary') {

                header("Location: veterinary.php");
                die;
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

   /* if($_SERVER['REQUEST_METHOD'] == "POST"){


        
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){

    
                $filename = "uploads/" . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $filename);

                if(file_exists($filename)){

                    $userid = $users_d['user_id'];
                    $query = "UPDATE farmer SET image = '$filename' WHERE user_id = '$userid' LIMIT 1";
                    $db = new Database();
                    $db->save($query);

                    header("Location: farmerProfile.php");
                }
                else {
                    $error_msg = "Please add a valide image";
                }
            }
            else {
                $error_msg = "only img type are allowed!";
            }

        }*/
        
 

    if ($_SERVER['REQUEST_METHOD'] == "POST" ) {

            $firstn = $_POST['firstn'];
            $lastn = $_POST['lastn'];
            $mail = $_POST['mail'];
            $password = $_POST['password'];
            $loc = $_POST['location'];
            $date = $_POST['date_birth'];

            $targetDir = "uploads/"; // Directory where images will be stored
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
            // Check if image file is a actual image or fake image
            if ($_FILES["image"]["tmp_name"]) {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    // Image is valid, move it to the target directory
                    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
                    $imagePath = $targetFile; // Save the path of the uploaded image
                } else {
                    echo "Error: File is not an image.";
                    exit;
                }
            } else {
                $imagePath = ""; // No image uploaded, set image path to empty
            }


            $userid = $users_d['user_id'];
            if(!empty($imagePath)){
                $query = "UPDATE farmer 
                        SET firstn='$firstn', lastn='$lastn', mail='$mail',
                        password='$password', image='$imagePath', location='$loc', date_birth='$date' 
                        WHERE user_id='$userid' LIMIT 1";
            }
            else {
            $query = "UPDATE farmer 
                        SET firstn='$firstn', lastn='$lastn', mail='$mail',
                        password='$password', location='$loc', date_birth='$date' 
                        WHERE user_id='$userid' LIMIT 1";
            }

            $query2 = "UPDATE users 
                        SET firstn='$firstn', lastn='$lastn', mail='$mail',
                        password='$password' WHERE user_id='$userid' LIMIT 1";

            $db = new Database(); // Assuming you have a Database class
            $db->save($query); // Assuming you have a method to execute SQL queries
            $db->save($query2);
            header("Location: farmerProfile.php");
            exit;

        }



    

    
    $image = "imgs/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg";
    if(file_exists($users_d['image'])){
        $image = $users_d['image'];
    }





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Farmer Profile</title>
    <link rel="stylesheet" href="css/all.min.css" />

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
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
                    <a class="" href="farmer.php">
                        <i class="fa-solid fa-house fa-fw"></i>
                        <span>Home</span>
                    </a>
                    </li>
                    <li>
                    <a class="active" href="farmerProfile.php">
                        <i class="fa-regular fa-user fa-fw"></i>
                        <span>Profile</span>
                    </a>
                    </li>
                    <li>
                    <a class="" href="all_vet.php">
                        <i class="fa-solid fa-user-doctor fa-fw"></i>
                        <span>veterinarians</span>
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
                    <img src="<?php echo $image; ?>" alt="">
                    <div class="info">
                        <span>@<?php echo $users_d['firstn'] . $users_d['lastn'] ?>
                            <br>
                            <p>Farmer</p>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="content">

        <div class="box1">
            
            <div class="head-box">
                <div class="title">
                    <a href="farmer.php" class="active">Your Profile</a>
                    <a href="all_vet.php" class="">veterinarians</a>
                    <span class="notification">
                        <i class="fa-regular fa-bell fa-lg"></i>
                    </span>
                </div>
            </div>
            <div class="body-box">
                
                <?php
                    echo "<div style='color: red;font-size: 16px;font-weight: bold;margin: 50px';>";
                    echo $error_msg ;
                    echo "</div>";
                ?>
                <div class="card" id="card">

                    <div class="info">

                    <img src="<?php echo $image; ?>" alt="">
                    <h2><?php echo $users_d['firstn'] . " " . $users_d['lastn'] ?></h2>
                    <p>farmer</p>
                    </div>

                    <div class="text">
                        <span>Your Id:</span>
                        <p><?php echo $users_d['user_id'] ?></p>
                    </div>
                    <div class="text">
                        <span>E-mail:</span>
                        <p><?php echo $users_d['mail']?></p>
                    </div>
                    <div class="text">
                        <span>Date of birth:</span>
                        <p><?php echo $users_d['date_birth']?></p>
                    </div>
                    <div class="text">
                        <span>location:</span>
                        <p><?php echo $users_d['location'] ?></p>
                    </div>

                    <input id="showupdatecard" type="submit" class="button" value="Update">
                    

                </div>

                <form action="#" method="POST" enctype="multipart/form-data">
                

                <div class="update-card" id="update-card">
                    <span id="close-card" class="close-icon">
                        <i class="fa-solid fa-xmark"></i>
                    </span>
                    <h1>Update Your Info</h1>
                    

                    <div class="lab">
                        <label>The New Image:</label>
                        
                            <input class="upload" type="file" name="image" id="image">

                    </div>
                    <br>

                    <div class="lab">
                        <label>First Name:</label>
                        <input type="text" name="firstn" value="<?php echo $users_d['firstn'] ?>">
                    </div>
                    <br>

                    <div class="lab">
                        <label >Last Name:</label>
                        <input type="text" name="lastn" value="<?php echo $users_d['lastn'] ?>">
                    </div>
                    <br>

                    <div class="lab">
                        <label>Email:</label>
                        <input type="mail" name="mail" value="<?php echo $users_d['mail'] ?>">
                    </div>
                    <br>

                    <div class="lab">
                        <label >Password:</label>
                        <input type="password" name="password" value="<?php echo $users_d['password'] ?>">
                    </div>
                    <br>

                    <div class="lab">
                        <label >date of birth</label>
                        <input type="date" name="date_birth" value="<?php echo $users_d['date_birth'] ?>">
                    </div>
                    <br>

                    <div class="lab">
                        <label for="location">location:</label>
                        <input type="text" name="location" value="<?php echo $users_d['location'] ?>">
                    </div>
                    <br>


                    <input class="button" type="submit"  value="Update">
                    
                </div>

                </form>

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
            <div class="body-box">
                
            </div>

        </div>
        
        </div>
    </div>

    <script>

            const card = document.getElementById('card');
            const updateCard = document.getElementById('update-card');
            const showupdatecard = document.getElementById('showupdatecard');
            const hide = document.getElementById('close-card');
            showupdatecard.addEventListener('click', function (event) {
            
                event.preventDefault();
                updateCard.style.display = 'block';
            });

            hide.addEventListener('click', function (event) {
            
            event.preventDefault();
            updateCard.style.display = 'none';
            });



    </script>
    

</body>
</html>