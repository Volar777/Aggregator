<?php

namespace Models;


class LoadFile
{
    public static function incomingFile($path)
    {
        if (isset($_FILES['xml']) ) {
                if (0 == $_FILES['xml']['error']) {
                    move_uploaded_file(
                        $_FILES['xml']['tmp_name'],
                        $path . '/' . 'file.xml'
                    );
                }else{
                    var_dump( 'Ошибка загрузки файла ',$_FILES);die;
                }
        }
    }
}