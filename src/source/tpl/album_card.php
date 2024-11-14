<?php
/** @var int $id */
/** @var string $title */
/** @var string $description */
/** @var string $user_id */
/** @var int $image_count */


if ($image_count > 0)
    $image_count = getPictureWord($image_count);
else
    $image_count = "Пусто";
?>
<div class="col">
    <div class="card shadow-sm h-100">
        <a href="?album=<?php echo $id;?>">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="150" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Название" preserveAspectRatio="xMidYMid slice" focusable="false"><title><?php echo $title;?></title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em"><?php echo $title;?></text></svg>
        </a>
        <div class="card-body">
            <p class="card-text"><?php echo $description;?></p></a>
            <div class="d-flex justify-content-between align-items-center">
                    <?php if ($user_id == session_id()){?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="edit_album(<?php echo $id;?>)">Edit</button>
                    </div>
                    <?php } ?>
                <small class="text-muted"><?php echo $image_count?></small>
            </div>
        </div>
    </div>
</div>
