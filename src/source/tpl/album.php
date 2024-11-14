<?php
/** @var object $conn */

require_once 'source/classes/Album.php';
require_once 'source/classes/Image.php';
use classes\Album;
use classes\Image;


$album = new Album($conn);
$row = $album->getAlbumById($_GET['album']);
if (empty($row)){
    die("Альбом не найден");
}
?>
<section class="py-5 text-center container">
    <div class="row py-lg-1">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-lighter">Альбом: <?php echo $row['title'];?></h1>
            <h6 class="fw-lighter"><?php echo $row['description'];?></h6>
            <a href="/">На главную</a>
        </div>
    </div>
</section>

<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-2">

            <div class="col">
                <div class="card shadow-sm h-100">
                    <a href="javascript:showAddA()">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="+" preserveAspectRatio="xMidYMid slice" focusable="false"><title>+</title><rect width="100%" height="100%" fill="#33395c"/><text x="50%" font-size="99" y="50%" fill="#eceeef" dy=".3em">+</text></svg>
                    </a>
                    <div class="card-body">
                        <p class="card-text">Нажмите что бы добавить картинку</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">&nbsp;
                            </div>
                            <small class="text-muted">&nbsp;</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $image = new Image($conn);
            foreach ($image->getImagesByAlbumId($_GET['album']) as $img) {
                echo render_template('source/tpl/image_card.php', $img);
            }
            ?>
        </div>

    </div>
</div>

<div class="modal fade" id="addImage" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="uploadForm" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Добавление картинки</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="img_name" placeholder="Название" aria-label="Название" aria-describedby="basic-addon1">
                </div>
                <div class="input-group  mb-3">
                    <span class="input-group-text">Описание:</span>
                    <textarea class="form-control" aria-label="Описание" id="img_desc"></textarea>
                </div>
                <div class="input-group  mb-3">
                    <input class="form-control" type="file" id="img" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary"  onclick="uploadFile()">Добавить</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editImage" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Редактирование данных картинки</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="img_edit_name" placeholder="Название" aria-label="Название" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group  mb-3">
                        <span class="input-group-text">Описание:</span>
                        <textarea class="form-control" aria-label="Описание" id="img_edit_desc"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary"  onclick="updateImage()">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="showImage" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content  modal-fullscreen">
            <div class="modal-header">
                <h5 class="modal-title" id="imageTitle">Название</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img alt="" src="source/img/test.jpg" id="imageFile" class="w-100">
                <p class="card-text" id="imageDescription">Тут длинное описание картинки текстом</p>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button type="button" class="btn" onclick="setLike(1)">
                        <i class="bi-hand-thumbs-up" id="like"></i>
                        <span class="badge bg-secondary" id="like_count">4</span>
                    </button>

                    <button type="button" class="btn" onclick="setLike(-1)">
                        <i class="bi-hand-thumbs-down" id="dlike"></i>
                        <span class="badge bg-secondary" id="dislike_count">6</span>
                    </button>
                </div>
            </div>

            <div class="modal-footer">
                <section class="w-100 ">
                    <div class="container">
                        <div class="row d-flex">
                            <div class="col-md-12">
                                <div class="card w-100">
                                    <div class="card-footer py-3  w-100 border-0" style="background-color: #f8f9fa;">
                                        <div class="d-flex flex-start w-100">
                                            <div data-mdb-input-init class="form-outline w-100">
                                                <span class="input-group-text">Ваш комментарий:</span>
                                                <textarea class="form-control" aria-label="Ваш комментарий" id="mycomment"></textarea>
                                            </div>
                                        </div>
                                        <div class="float-end mt-2 pt-1">
                                            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-sm" onclick="sendComment()">Опубликовать</button>
                                        </div>
                                    </div>

                                    <div id="comments">
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
    var album_id = <?php echo $_GET['album']?>;
    var image_id = 0;

    function showAddA() {
        addImage.show();
    }
    function setLike(like){

        if (like === 1 && $('#like').attr('class') === "bi-hand-thumbs-up-fill") like=0;
        if (like === -1 && $('#dlike').attr('class') === "bi-hand-thumbs-down-fill") like=0;

        var formData = new FormData();
        formData.append('action', 'set_like');
        formData.append('image_id', image_id);
        formData.append('like', like);

        $.ajax({
            url: 'source/api/image.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#like_count').html(res.like_count);
                    $('#dislike_count').html(res.dislike_count);
                    likeToggle(like);
                }
            }
        });
    }

    function updateImage(){
        var formData = new FormData();
        formData.append('action', 'edit_image');
        formData.append('image_id', image_id);
        formData.append('title', $('#img_edit_name').val());
        formData.append('description', $('#img_edit_desc').val());

        $.ajax({
            url: 'source/api/image.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                res = JSON.parse(response);
                if (res.status === 'success') {
                    location.reload();
                }else
                    alert(res.message);
            }
        });
    }

    function edit_image(id){
        var formData = new FormData();
        formData.append('id', id);
        formData.append('action', 'get_image');
        image_id = id;
        $.ajax({
            url: 'source/api/image.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#img_edit_name').val(res.title);
                    $('#img_edit_desc').val(res.description);
                    editImage.show();
                }else
                    alert(res.message);
            }
        });
    }

    function sendComment(){
        var formData = new FormData();
        formData.append('image_id', image_id);
        formData.append('action', 'add_comment');
        formData.append('text', $('#mycomment').val());

        $.ajax({
            url: 'source/api/image.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#mycomment').val("");
                    $('#comments').html(res.comments);
                }else
                    alert(res.message);
            }
        });
    }

    function likeToggle(like){
        switch (like) {
            case -1:
                $('#dlike').removeClass('bi-hand-thumbs-down').addClass('bi-hand-thumbs-down-fill');
                $('#like').removeClass('bi-hand-thumbs-up-fill').addClass('bi-hand-thumbs-up');
                break;
            case 0:
                $('#dlike').removeClass('bi-hand-thumbs-down-fill').addClass('bi-hand-thumbs-down');
                $('#like').removeClass('bi-hand-thumbs-up-fill').addClass('bi-hand-thumbs-up');
                break;
            case 1:
                $('#dlike').removeClass('bi-hand-thumbs-down-fill').addClass('bi-hand-thumbs-down');
                $('#like').removeClass('bi-hand-thumbs-up').addClass('bi-hand-thumbs-up-fill');
                break;
        }
    }

    function showImageA(id) {
        var formData = new FormData();
        formData.append('id', id);
        formData.append('action', 'get_image');
        image_id = id;
        $.ajax({
            url: 'source/api/image.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                res = JSON.parse(response);
                if (res.status === 'success') {
                    likeToggle(res.like);
                    $('#like_count').html(res.like_count);
                    $('#dislike_count').html(res.dislike_count);
                    $('#imageTitle').html(res.title);
                    $('#mycomment').val("");
                    $('#comments').html(res.comments);
                    $('#imageDescription').html(res.description);
                    $('#imageFile').attr('src',"source/img/"+res.file_path);
                    showImage.show();
                }else
                    alert(res.message);
            }
        });
    }



    function uploadFile() {
        var formData = new FormData();
        var fileInput = document.getElementById('img');

        var file = fileInput.files[0];
        var title = $("#img_name").val();
        var description = $("#img_desc").val();

        formData.append('action', 'add_image');
        formData.append('img', file);
        formData.append('album_id', album_id);
        formData.append('title', title);
        formData.append('description', description);

        $.ajax({
            url: 'source/api/image.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                res = JSON.parse(response);
                if (res.status === 'success')
                    location.reload();
                else
                    alert(res.message);
            }
        });
    }

    $(document).ready(function() {
        editImage = new bootstrap.Modal(document.getElementById('editImage'), {
            keyboard: false
        });
        addImage = new bootstrap.Modal(document.getElementById('addImage'), {
            keyboard: false
        });
        showImage = new bootstrap.Modal(document.getElementById('showImage'), {
            keyboard: false
        });
    });
</script>