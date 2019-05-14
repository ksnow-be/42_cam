<?php
session_start();
if ($_FILES['data']){
    $uploaddir = '/home/r/rfb7925m/rating21.reznikoff.pro/public_html/room/img/';
    $uploadfile = $uploaddir . time().'.jpg';

    if (!move_uploaded_file($_FILES['data']['tmp_name'], $uploadfile)) {
        echo "Возможная атака с помощью файловой загрузки!\n";
    }
    die();
}else{
    if ($_GET['pass'] == 'mysuperpassword'){
        $_SESSION['admin'] = true;
    }
    if ($_SESSION['admin'] && $_GET['unset']){
        unlink($_GET['unset']);
    }
    $fileList = glob('img/*');
    if(($totalImg = count($fileList)) > 30){
        $delete = true;
    }
    if($delete){
        $deleted = 0;
    }
    foreach($fileList as $filename){
        if ($_SESSION['admin'] && $_GET['unsetall'] == 1){
            unlink($filename);
        }
        if ($delete && $deleted < ($totalImg - 5)){
            unlink($filename);
            $deleted++;
        }
        $out_arr[] = $filename;
    }
}
include 'header.php';

$out_arr = array_reverse($out_arr);
$cnt = 0;
foreach ($out_arr as $file){
    if ($cnt > 2){
        break;
    }
    $data_arr = explode('.',basename($file));
    $data = $data_arr[0];
    if ($_SESSION['admin']){
        $btn = '<a href="/room/?unset='.$file.'" class="btn btn-small btn-danger">Удалить</a>';
    }else{
        $btn = '';
    }
    echo '                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" src="'.$file.'" style="height: 225px; width: 100%; display: block;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">'.date("m.d.y H:i:s",$data).'</small>'.$btn.'
                            </div>
                        </div>
                    </div>
                </div>';
    $cnt++;
}


include 'footer.php';
