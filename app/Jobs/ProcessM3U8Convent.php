<?php

namespace App\Jobs;

use App\Enums\VideoConventType;
use App\Repository\VideosRepository;
use AYazdanpanah\FFMpegStreaming\FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ProcessM3U8Convent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $primaryKey;

    /**
     * Create a new job instance.
     *
     * @param int $primaryKey
     * @return void
     */
    public function __construct($primaryKey)  {
        $this->primaryKey = $primaryKey;
    }

    /**
     * Execute the job.
     *
     * @param $repository
     * @return void
     */
    public function handle(VideosRepository $repository) {
        $source = $repository->getDataByPrimaryKey($this->primaryKey);
        $source->status = VideoConventType::UPLOAD_CONVENT_M3U8;
        $sourceFilePath = storage_path('app/public'). DIRECTORY_SEPARATOR . $source->source_src;
        $source->save();
        Storage::disk('public')->makeDirectory('m3u8');

        $m3u8FilePath =  storage_path('app/public'). DIRECTORY_SEPARATOR . 'm3u8' . DIRECTORY_SEPARATOR . uniqid() .'.m3u8';
        $config = [
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout'          => 3600 * 60 * 5, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ];

        FFMpeg::create($config)
            ->open($sourceFilePath)
            ->HLS()
            ->X264() // the format of the video.for use another formats, see Traits\Formats
            ->autoGenerateRepresentations() // auto generate representations
            ->save($m3u8FilePath); // it can pass a path to the method or it can be null

        $repository->modifyConventResult($this->primaryKey,$m3u8FilePath,VideoConventType::UPLOAD_CONVENT_STATUS);

    }
}
