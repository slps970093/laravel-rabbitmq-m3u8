<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessM3U8Convent;
use AYazdanpanah\FFMpegStreaming\FFMpeg;
use Illuminate\Http\Request;
use App\Repository\VideosRepository;
use Illuminate\Support\Facades\Storage;
use Interop\Queue\Queue;

class VideoController extends Controller {

    private $videoResposity;

    public function __construct(VideosRepository $repository ) {
        $this->videoResposity = $repository;
    }

    public function index() {
        return view('video.index');
    }

    public function upload(Request $request) {
        Storage::disk('public')->makeDirectory('videos');
        $filepath = Storage::disk('public')->put('videos',$request->file('video'));

        $primaryKey = $this->videoResposity->createConventM3U8video($filepath,$request->post('name'));

        dispatch(new ProcessM3U8Convent($primaryKey))->onQueue('m3u8');


        return response()->json(['status' => true],201);
    }


    public function showVideo() {

    }
}
