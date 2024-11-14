<?php
require_once 'source/classes/Album.php';
use classes\Album;

/** @var object $conn */
?>
<section class="py-5 text-center container">
    <div class="row py-lg-1">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-lighter">Альбомы</h1>
        </div>
    </div>
</section>

<div class="album py-5 bg-light">
    <div class="container">

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-2">

            <div class="col">
                <div class="card shadow-sm h-100">
                    <a href="javascript:showAddA()">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="150" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="+" preserveAspectRatio="xMidYMid slice" focusable="false"><title>+</title><rect width="100%" height="100%" fill="#33395c"/><text x="50%" font-size="99" y="50%" fill="#eceeef" dy=".3em">+</text></svg>
                    </a>
                    <div class="card-body">
                        <p class="card-text w-100" align="center">Добавление нового альбома</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">&nbsp;
                            </div>
                            <small class="text-muted">&nbsp;</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $album = new Album($conn);
            foreach ($album->getAlbums() as $album) {
                echo render_template('source/tpl/album_card.php', $album);
            }
            ?>
        </div>

    </div>
</div>

<div class="modal fade" id="myModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Новый альбом</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="album_name" placeholder="Название" aria-label="Название" aria-describedby="basic-addon1">
                </div>
                <div class="input-group">
                    <span class="input-group-text">Описание:</span>
                    <textarea class="form-control" aria-label="Описание" id="album_desc"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="createAlb">Создать</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="album_label">Новый альбом</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="input-group mb-3">
                    <input type="hidden" id="album_edit_id" value="">
                    <input type="text" class="form-control" id="album_edit_name" placeholder="Название" aria-label="Название" aria-describedby="basic-addon1">
                </div>
                <div class="input-group">
                    <span class="input-group-text">Описание:</span>
                    <textarea class="form-control" aria-label="Описание" id="album_edit_desc"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="editAlb">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<script type="application/javascript">
    function showAddA() {
        myModal.show();
    }
    function edit_album(id){
        let album = {
            action: "get_album",
            id: id
        }
        $.ajax({
            type: 'POST',
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            url: 'source/api/album.php',
            data: JSON.stringify(album),
        }).always(function (data) {
            if (data.status !== 200) {
                $("#album_edit_id").val(data.id);
                $("#album_edit_name").val(data.title);
                $("#album_edit_desc").val(data.description);
                editModal.show();
            }
        });
    }

    $(document).ready(function() {
        myModal = new bootstrap.Modal(document.getElementById('myModal'), {
            keyboard: false
        })
        editModal = new bootstrap.Modal(document.getElementById('editModal'), {
            keyboard: false
        })

        document.getElementById('editAlb').addEventListener('click', (e) => {
            let album = {
                action: "edit_album",
                id: $("#album_edit_id").val(),
                title: $("#album_edit_name").val(),
                description: $("#album_edit_desc").val()
            }
            $.ajax({
                type: 'POST',
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                url: 'source/api/album.php',
                data: JSON.stringify(album),
            }).always(function (data) {
                if (data.status !== 201) {
                    alert(data.msg);
                }else{
                    location.reload();
                }
            });
        });

        document.getElementById('createAlb').addEventListener('click', (e) => {
            let album = {
                action: "add_album",
                title: $("#album_name").val(),
                description: $("#album_desc").val()
            }
            $.ajax({
                type: 'POST',
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                url: 'source/api/album.php',
                data: JSON.stringify(album),
            }).always(function (data) {
                if (data.status !== 201) {
                    alert(data.msg);
                }else{
                    location.reload();
                }
            });
        });

    });
</script>
