<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use App\Models\SettingComponentObjectModel;

class SlackComponentObjectModel
{
    const CHANNEL1 = 'channel1';
    const CHANNEL2 = 'channel2';
    const CHANNEL3 = 'channel3';

    private static $last_channel_category = null;

    public static function setLastSlackCategory($slack_category)
    {
        self::$last_channel_category = $slack_category;
    }

    public static function getLastSlackCategory($default = null)
    {
        return self::$last_channel_category ?: $default;
    }

    public static function getSlackCategoryWithChannelToken($channel_token)
    {
        $slack = SettingComponentObjectModel::get('slack', null);

        foreach (Arr::get($slack, "channels") as $news_category => $channel) {
            if ($channel['token'] === $channel_token) {
                return $news_category;
            }
        }

        return null;
    }

    public static function getTokensWithSlackCategory($slack_category)
    {
        $tokens = [];

        $slack = SettingComponentObjectModel::get('slack', null);

        
        $channel_key = Arr::get($slack, "channels.$slack_category", [$slack_category]);    
        $channel_bot = Arr::get($channel_key, 'bot');
        $channel_token = Arr::get($channel_key, 'token');
        if ($bot =Arr::get($slack, "bots.$channel_bot")) {
            $bot_token = Arr::get($bot, 'token');
            $tokens[] = ['bot_token' => $bot_token, 'channel_token' => $channel_token];
        }

        return $tokens;
    }


    public static function async($slack_category, $msg, $data = [])
    {

        $slack_call_idx = SettingComponentObjectModel::get('slack_call_idx', 0);
        $slack_call_idx++;
        SettingComponentObjectModel::set('slack_call_idx', $slack_call_idx);
        $data = array_merge($data, ['slack_call_idx' => $slack_call_idx]);

        $tokens = self::getTokensWithSlackCategory($slack_category);
        foreach ($tokens as $token) {
            \App\Jobs\SendSlackJob::dispatch(
                Arr::get($token, 'bot_token'),
                Arr::get($token, 'channel_token'),
                $msg,
                $data
            );
        }
    }

    public static function send($bot_token, $channel_token, $msg, $data = [])
    {

        $json = [
            "channel" => $channel_token,
            "text" => $msg,
        ];

        if ($data) {
            $json = array_merge($json, $data);
        }

        $client = new Client(['base_uri' => 'https://slack.com/api/']);
        $client->request('POST', 'chat.postMessage', [
            'headers' => ['Authorization' => 'Bearer ' . $bot_token],
            'json' => $json,
        ]);

        self::setLastSlackCategory(null);
    }

    
}
