<!-- 查看资讯详情 -->
{extend name="public/modal-frame"}
{block name="head_top"}
<link href="https://imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.css" rel="stylesheet">
<script src="https://imgcache.qq.com/open/qcloud/video/tcplayer/libs/hls.min.0.13.2m.js"></script>
<script src="https://imgcache.qq.com/open/qcloud/video/tcplayer/tcplayer.v4.1.min.js"></script>
<script src="{__FRAME_PATH}js/plugins/chosen/chosen.jquery.js"></script>
<style>
.header {
    display: flex;
    width: 100%;
    height: 60px;
}
.detail {
    flex: 1;
    padding-left: 8px;
    padding-top: 3px;
}
.user-head-img {
    height: 50px;
    width: 50px;
    border-radius: 50%; 
    border: solid 1px #fafafa;
    overflow: hidden;
}
.user-head-img img {
    width: 100%;
    height: 100%;
}
.detail p:first-of-type {
    font-size: 18px;
    margin-bottom: 3px;
    font-weight: 600;
}

.detail p:last-of-type {
    font-size: 14px;
}
h1 {
    margin-top: 5px;
    color: black;
}
a {
    color: #02A7F0;
    display: inline-block;
    margin-top: 10px;
    text-decoration: underline;
}
.body {
    margin-top: 20px;
}
.play-video {
    display: none;
}
</style>
{/block}
{block name="content"}
{if condition="$type eq 1"}
                <div class="container">
                    <!--<video src="{$info.video_url}" autoplay controls loop></video>-->
                    <div id="warn1" style="display: none;font-weight: bold;">媒体文件不存在！</div>
                    <video style="max-width: 100%;" id="player1" preload="auto" autoplay controls loop playsinline webkit-playsinline></video>
                </div>
            {else}
            <div class="container">
                <div class="header">
                    <div class="user-head-img"><img src="{$info.user.avatar}" alt=""></div>
                    <div class="detail">
                        <p class="username">{$info.user.nickname}</p>
                        <p class="time">{$info.create_time}</p>
                    </div>
                </div>
                <h1>{$info.title}</h1>
                <div class="body">
                    <p class="content"></p>
                    <a href="javascript:;" class="a-btn">点击播放视频→</a>
                    <div class="play-video">
                        <!--<video src="{$info.video_url}" controls autoplay></video>-->
                        <div id="warn2" style="display: none;font-weight: bold;">媒体文件不存在！</div>
                        <video style="max-width: 100%;" id="player2" preload="auto" autoplay controls playsinline webkit-playsinline></video>
                    </div>
                </div>
            </div>
{/if}
{/block}
{block name="script"}
<script>
if ($('.content').length > 0) {
    var oldContentHtml = $('.content').html({$info.content}).html();
    var newContentHtml = unescape(oldContentHtml.replace(/\\u/g, '%u'));
    $('.content').html(newContentHtml);
}

var fileID = '{$info.video_id}';
if ($('#player2').length > 0) {
    $('.body').on('click', '.a-btn', function() {
        $('.play-video').css('display', 'block');
        if (fileID == '') {
            $('#warn2').show();
            $('#player2').hide();
        } else {
            var player2 = TCPlayer('player2', { // player-container-id 为播放器容器 ID，必须与 html 中一致
                fileID: fileID, // 请传入需要播放的视频 filID（必须）
                appID: {$info.app_id}, // 请传入点播账号的 appID（必须）
                psign: '{$info.psign}'
            });
        }
    })
}

if ($('#player1').length > 0) {
    window.onload = function() {
        if (fileID == '') {
            $('#warn1').show();
            $('#player1').hide();
        } else {
            var player1 = TCPlayer('player1', {
                fileID: fileID,
                appID: {$info.app_id},
                psign: '{$info.psign}'
            });
        }
    }
}

</script>
{/block}