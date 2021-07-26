<?php

public function post()
{
    $subjects = array_map(static function ($subjectsArr) {
        return [
            'id' => $subjectsArr['id'],
            'ref' => $subjectsArr['reference']['name'],
        ];
    }, \App\Post::with('reference')->orderBy('id', 'ASC')->get()->toArray());
    $data = json_encode($subjects,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    header('Content-type: application/json; charset=UTF-8');
    $file = 'posts.json';
    $destinationPath=public_path()."/upload/json/";
    if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
    File::put($destinationPath.$file,$data);
    return response()->download($destinationPath.$file);
}
