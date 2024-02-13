<?php
/* 附件上传，这里直接返回成功 */
sleep(3);
$ext = [ '.png','.jpg','.jpeg','.bmp','.gif','.zip','.rar','.doc','.xls','.txt','.ppt','.rm','.exe','.rmvb','.mp4','.wma','.wav','.lrc','.iso','.xlsx','.7z'
];
$fileUrl = './upload/file/'.date('Ymd').'/'.md5(uniqid()).$ext[array_rand($ext, 1)];
echo json_encode(['status' => 200, 'info' => '上传成功', 'class' => 'success', 'id' => rand(1, 9999), 'file_url' => $fileUrl]);