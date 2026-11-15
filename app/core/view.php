
<?php

class View
{
    public static function render($file, $data = [])
    {
        extract($data);

        $path = __DIR__ . '/../views/' . $file . '.php';

        if (file_exists($path)) {
            require $path;
        } else {
            die("Halaman tidak ditemukan: " . $file);
        }
    }
}

?>
