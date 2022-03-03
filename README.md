# laravel_Slackbot

* 개인 로컬에 도커파일을 활용하여 실행합니다.
* mysql 5.7 php 8 laravel 8 nginx로 구성되어있습니다.


---

# Settings Table

* 해당 슬랙봇을 사용하기 위해서는 마이그레이션 된 settings 테이블에 아래의 값을 넣어야 합니다.
* key : 'slack'
* value : 아래 Json Data (최소 3개의 채널 Id값)
```
{ "bots": {"test": {"token": "xoxb-Yourcode"}}, "channels": { "channel1": {"bot": "test","token": "YourChannelId"}, "channel2": {"bot": "test","token": "YourChannelId"}, "channel3": {"bot": "test","token": "YourChannelId"} } }
```

---

# How To Use

* Component Object Model 형식으로 제작되었기 때문에 실행 메서드를 호출함으로 실행 가능.

* Input Example
```
$ php artisan tinker
Psy Shell v0.11.2 (PHP 8.0.16 — cli) by Justin Hileman
>>> App\Models\SlackComponentObjectModel::async(SlackComponentObjectModel::CHANNEL3, 'MyNameIsMingyu!!!!! ');
=> null
>>> App\Models\SlackComponentObjectModel::async(SlackComponentObjectModel::CHANNEL3, 'this is channel 3@@@!@#!@#!#');              
=> null
```
* Output Example
<img width="1078" alt="스크린샷 2022-03-02 오후 6 10 29" src="https://user-images.githubusercontent.com/24973648/156474161-59dc3ec3-b124-45f8-9818-7d9ca97fc743.png">


