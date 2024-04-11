<?php
    $image = "imgs/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg";
    if($row_users['type'] === 'farmer'){
        if(file_exists($row_farmer['image'])){
            $image = $row_farmer['image'];
        }
    }
    elseif($row_users['type'] === 'veterinary'){
        if(file_exists($row_vet['image'])){
            $image = $row_vet['image'];
        }
    }


?>
    <div class="post-list">
        <div class="info">
            <img src="<?php echo $image; ?>" alt="">
            <span> @<?php echo $row_users['firstn'] . " " . $row_users['lastn'] ?> 
                <br><p>Farmer</p>
            </span>
            <p><?php echo $row['date'] ?></p>
        </div>
        <div class="text">
            <p><?php echo $row['post'] ?></p>
        </div>
        <div class="image">
            <?php if ($row['has_image'] == 1): ?>
                <img src="<?php echo $row['image']; ?>" alt="">
            <?php endif; ?>
        </div>
        <div class="icons">
            <i class="fa-regular fa-heart"></i>
            <i class="fa-regular fa-comment"></i>
        </div>
    </div>