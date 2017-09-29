<?php
/**
 * Created by PhpStorm.
 * User: wzy
 * Date: 2017/9/27
 * Time: 下午5:27
 */

function walkDirectoryA($root, $filter = null) {
    if (!is_dir($root)) {
        return [];
    }

    $fh = opendir($root);
    $file = [];
    while (($tmp = readdir($fh))) {
        if ($tmp == '.' || $tmp == '..') {
            // 过滤目录中的. 和.. , 一个代表当前目录， 一个代表上级目录
            continue;
        }
        $full_path = $root . '/' . $tmp;
        if (is_file($full_path)) {
            // 如果当前文件是普通文件， 保留到返回列表中
            $file[] = $full_path;
        } elseif (is_dir($full_path)) {
            // 如果当前文件为目录，继续深一层的遍历
            $sub_files = walkDirectoryA($full_path);
            // 将子目录的文件列表，合并到当前文件列表
            $file = array_merge($file, $sub_files);
        }
    }

    return $file;
}

$p1 = realpath($argv[1]);
$p2 = realpath($argv[2]);

$flist = walkDirectoryA($p1);

foreach ($flist as $f) {

    $t = str_replace($p1, $p2, $f);
    $t = str_replace('.htm', '.jsp', $t);
    $dir = dirname($t);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $c = file_get_contents($f);
    file_put_contents($t, $c);
    echo $f . "   =>   " . $t . "\n";
}