<?php

namespace App\Jobs;

use App\Models\SlackComponentObjectModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSlackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bot_token;
    protected $channel_token;
    protected $msg;
    protected $data;

    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bot_token, $channel_token, $msg, $data)
    {
        $this->bot_token = $bot_token;
        $this->channel_token = $channel_token;
        $this->msg = $msg;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 슬랙은 전송 실패시 30초 뒤에 한번더 시도한다
        // 이떄도 실패했을때 failed queue 에 등록됨
        try {
            SlackComponentObjectModel::send($this->bot_token, $this->channel_token, $this->msg, $this->data);
        } catch (\Throwable $exception) {
            if ($this->attempts() > 1) {
                throw $exception;
            }
            $this->release(30);
            return;
        }
    }
}
