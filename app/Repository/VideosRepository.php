<?php

namespace App\Repository;


use App\Model\Video;
use App\Enums\VideoConventType;

class VideosRepository {

    private $video;


    public function __construct(Video $video) {
        $this->video = $video;
    }


    public function createConventM3U8video($path, $name) {
        $data = $this->video->create([
            'name' => $name,
            'source_src' => $path,
            'status' => VideoConventType::UPLOAD_STATUS
        ]);

        return $data->id;
    }

    public function getDataByPrimaryKey($primaryKey) {
        return $this->video->find($primaryKey);
    }


    public function modifyConventResult($primaryKey,$filePath,$status) {
        $data = $this->video->find($primaryKey);
        $data->m3u8_src = $filePath;
        $data->status = $status;
        $data->save();
    }

}