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




