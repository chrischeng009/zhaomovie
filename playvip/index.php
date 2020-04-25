<?php
//version  v1.1
require_once("user.php");
require_once("crypt.php");
@$url = htmlspecialchars($_GET['url'] ? $_GET['url'] : $_POST['url']);
if (!isset($_GET['url'])) {
    exit('<html><meta name="robots" content="noarchive"><head><title>智能解析系统 - 全网视频在线解析服务 - QQ群：971255789</title></head><style>h1{color:#00A0E8; text-align:center; font-family: Microsoft Jhenghei;}p{color:#f90; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style><body bgcolor="#000000"><table width="100%" height="100%" align="center"><td align="center"><h1>欢迎使用宝思智能解析系统</h1><p>如有任何问题请联系管理员处理，本站第一时间为您解决后顾之忧</p></table></body></html>');
} else if (isset($_GET['url']) && $_GET['url'] == '') {
    exit('<html><meta name="robots" content="noarchive"><head><title>智能解析系统 - 全网视频在线解析服务 - QQ群：971255789</title></head><style>h1{color:#00A0E8; text-align:center; font-family: Microsoft Jhenghei;}p{color:#f90; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style><body bgcolor="#000000"><table width="100%" height="100%" align="center"><td align="center"><h1>请输入视频链接地址</h1><p>欢迎使用本站解析服务，如有任何问题请联系管理员</p></table></body></html>');
}
$url = url_convert($url);
#url格式化
function url_convert($url)
{
    if (strstr($url, 'miguvideo.com') == true) {
        preg_match('|cid=(\d+?)|U', $url, $cid);
        $url = $cid['1'] . '@miguvideo';
    } else if (strstr($url, 'm.v.qq.com') == true) {
        parse_str(str_replace('?', '&', $_SERVER['QUERY_STRING']), $list);
        if ($list['vid'] && $list['cid']) {
            $url = 'https://v.qq.com/x/cover/' . $list['cid'] . '/' . $list['vid'] . '.html';
        } else if ($list['vid']) {
            $url = 'https://v.qq.com/x/cover/' . $list['vid'] . '/' . $list['vid'] . '.html';
        } else if ($list['cid']) {
            $url = 'https://v.qq.com/x/cover/' . $list['cid'] . '.html';
        }
    } else if (strstr($url, 'm.fun.tv') == true) {
        parse_str(str_replace('?', '&', $_SERVER['QUERY_STRING']), $list);
        if ($list['mid'] && $list['vid']) {
            $url = 'https://www.fun.tv/vplay/g-' . $list['mid'] . '.v-' . $list['vid'];
        } else if ($list['mid']) {
            $url = 'https://www.fun.tv/vplay/g-' . $list['mid'] . '/';
        } else if ($list['vid']) {
            $url = 'https://www.fun.tv/vplay/v-' . $list['vid'] . '/';
        }
    }
    return $url;
}
#通信加密秘钥生成
$crypt = new crypt();
$key = $user['auth_code'] ? $user['auth_code'] : '123';
$keys = $crypt->authcode($_SERVER['SERVER_NAME'] . '||' . $url, 'ENCODE', $key, 0);
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta name="referrer" content="never">
    <meta http-equiv="X-UA-Compatible" content="IE=11"/>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport"
          name="viewport">
    <title><?php echo $user['title']; ?></title>
    <link href="<?php echo $user['path']; ?>/css/style.css" rel="stylesheet">
    <link href="https://cdn.staticfile.org/dplayer/1.25.0/DPlayer.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://g.alicdn.com/de/prismplayer/2.8.8/skins/default/aliplayer-min.css"/>
    <!--加载播放器-->
    <script src="https://cdn.staticfile.org/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/hls.js/8.0.0-beta.3/hls.min.js"></script>
    <script src="https://cdn.staticfile.org/dplayer/1.25.0/DPlayer.min.js"></script>
    <script type="text/javascript" src="<?php echo $user['path']; ?>/Ckplayer/Ckplayer.min.js"></script>
    <script type="text/javascript" charset="utf-8"
            src="https://g.alicdn.com/de/prismplayer/2.8.8/aliplayer-min.js"></script>
</head>
<style>
    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: #000;
        text-align: center;
        color: #fff;
    }

    #video {
        padding: 0;
        margin: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        color: #999;
    }

    .content {
        position: fixed;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%)
    }
</style>
<body>
<div id="video" style="width:100%;height:100%;">
    <div id="time" style="">
        <div class="content">
            <div class="column">
                <div class="container animation-<?php echo $user['animation'] ? $user['animation'] : 1 ?>">
                    <div class="shape shape1"></div>
                    <div class="shape shape2"></div>
                    <div class="shape shape3"></div>
                    <div class="shape shape4"></div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
<script>
    window.onload = function() {
        var referef_type = '<?php echo REFERER_TYPE?>';
        var referef_url = '<?php echo REFERER_URL?>';
        var html = '<?php echo REFERER_INFO?>';

        /*
         * 加密工具已经升级了一个版本，目前为 sojson.v5 ，主要加强了算法，以及防破解【绝对不可逆】配置，耶稣也无法100%还原，我说的。;
         * 已经打算把这个工具基础功能一直免费下去。还希望支持我。
         * 另外 sojson.v5 已经强制加入校验，注释可以去掉，但是 sojson.v5 不能去掉（如果你开通了VIP，可以手动去掉），其他都没有任何绑定。
         * 誓死不会加入任何后门，sojson JS 加密的使命就是为了保护你们的Javascript 。
         * 警告：如果您恶意去掉 sojson.v5 那么我们将不会保护您的JavaScript代码。请遵守规则 */

        ;var encode_version = 'sojson.v5', pujsp = '__0x7bfa0',  __0x7bfa0=['GkHDgk3DgA==','wrHDiSXDncOG','w5bDjcKQwp0o','wrbDnWfDlVzCh0dq','wqbDhcO2wojCtsOrwpo4MA==','w4TCnG1twq8=','5Luu6IGW5YqT6ZifZS3DrU8VWk7Dh8OD','wo/Dkls4','wovDn8OeFw==','J8Kkcg==','w4LCrsKvIsO9','wqbDmsOwwpLCrQ==','wqnDvCMkw4I=','FsK+d8OCHMOUQsOy','dFM6','d1sIw7PDicOhw5TDnA==','w5vDm2kd','VMKpEA==','T8OcXFXCtA==','w4laEMKDEQ=='];(function(_0x6da147,_0x1a0184){var _0x304917=function(_0x41253f){while(--_0x41253f){_0x6da147['push'](_0x6da147['shift']());}};_0x304917(++_0x1a0184);}(__0x7bfa0,0x133));var _0x1dc0=function(_0xd77257,_0x4c3901){_0xd77257=_0xd77257-0x0;var _0x3f7bcf=__0x7bfa0[_0xd77257];if(_0x1dc0['initialized']===undefined){(function(){var _0xc1d2c6=typeof window!=='undefined'?window:typeof process==='object'&&typeof require==='function'&&typeof global==='object'?global:this;var _0x2fe168='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0xc1d2c6['atob']||(_0xc1d2c6['atob']=function(_0x47a793){var _0x43cb4e=String(_0x47a793)['replace'](/=+$/,'');for(var _0x186b6d=0x0,_0x387334,_0x14c60d,_0x912b8=0x0,_0x2ec5cf='';_0x14c60d=_0x43cb4e['charAt'](_0x912b8++);~_0x14c60d&&(_0x387334=_0x186b6d%0x4?_0x387334*0x40+_0x14c60d:_0x14c60d,_0x186b6d++%0x4)?_0x2ec5cf+=String['fromCharCode'](0xff&_0x387334>>(-0x2*_0x186b6d&0x6)):0x0){_0x14c60d=_0x2fe168['indexOf'](_0x14c60d);}return _0x2ec5cf;});}());var _0xada202=function(_0x149626,_0x1cfaaf){var _0x2323e4=[],_0x41439c=0x0,_0x5db73e,_0x1ed6c0='',_0x5aa357='';_0x149626=atob(_0x149626);for(var _0x32f67b=0x0,_0xfc2466=_0x149626['length'];_0x32f67b<_0xfc2466;_0x32f67b++){_0x5aa357+='%'+('00'+_0x149626['charCodeAt'](_0x32f67b)['toString'](0x10))['slice'](-0x2);}_0x149626=decodeURIComponent(_0x5aa357);for(var _0x4765e0=0x0;_0x4765e0<0x100;_0x4765e0++){_0x2323e4[_0x4765e0]=_0x4765e0;}for(_0x4765e0=0x0;_0x4765e0<0x100;_0x4765e0++){_0x41439c=(_0x41439c+_0x2323e4[_0x4765e0]+_0x1cfaaf['charCodeAt'](_0x4765e0%_0x1cfaaf['length']))%0x100;_0x5db73e=_0x2323e4[_0x4765e0];_0x2323e4[_0x4765e0]=_0x2323e4[_0x41439c];_0x2323e4[_0x41439c]=_0x5db73e;}_0x4765e0=0x0;_0x41439c=0x0;for(var _0x5b4246=0x0;_0x5b4246<_0x149626['length'];_0x5b4246++){_0x4765e0=(_0x4765e0+0x1)%0x100;_0x41439c=(_0x41439c+_0x2323e4[_0x4765e0])%0x100;_0x5db73e=_0x2323e4[_0x4765e0];_0x2323e4[_0x4765e0]=_0x2323e4[_0x41439c];_0x2323e4[_0x41439c]=_0x5db73e;_0x1ed6c0+=String['fromCharCode'](_0x149626['charCodeAt'](_0x5b4246)^_0x2323e4[(_0x2323e4[_0x4765e0]+_0x2323e4[_0x41439c])%0x100]);}return _0x1ed6c0;};_0x1dc0['rc4']=_0xada202;_0x1dc0['data']={};_0x1dc0['initialized']=!![];}var _0x192b12=_0x1dc0['data'][_0xd77257];if(_0x192b12===undefined){if(_0x1dc0['once']===undefined){_0x1dc0['once']=!![];}_0x3f7bcf=_0x1dc0['rc4'](_0x3f7bcf,_0x4c3901);_0x1dc0['data'][_0xd77257]=_0x3f7bcf;}else{_0x3f7bcf=_0x192b12;}return _0x3f7bcf;};if(referef_type==0x1){var is_ref=check_ref();if(!is_ref){$(_0x1dc0('0x0','Aax4'))[_0x1dc0('0x1','EPQa')](html);}else{play();}}else{play();}function getDomain(){var _0x1494fe={'Xzstf':_0x1dc0('0x2','tiGS'),'NFaSN':function _0x3ea5aa(_0x339852){return _0x339852();}};var _0x4a2ae1='1|2|3|0|4'[_0x1dc0('0x3','i9my')]('|'),_0x223185=0x0;while(!![]){switch(_0x4a2ae1[_0x223185++]){case'0':if(_0x44e622){_0x44e622=_0x383a50[_0x1dc0('0x4','(pl$')]('//')[0x1][_0x1dc0('0x5','hOyE')]('/')[0x0];}continue;case'1':var _0x44e622='';continue;case'2':var _0x383a50=document[_0x1dc0('0x6','O7O^')];continue;case'3':if(!_0x383a50){if(_0x1dc0('0x7','UX%S')!==_0x1494fe['Xzstf']){_0x1494fe['NFaSN'](play);}else{_0x383a50=window[_0x1dc0('0x8','UX%S')][_0x1dc0('0x9','#ZSU')];}}continue;case'4':return _0x44e622;}break;}}function check_ref(){var _0x16c9f9={'mHENX':function _0x2cecd3(_0x55b46c){return _0x55b46c();},'HOKhq':function _0x131af1(_0x1cbdf7,_0x25b166){return _0x1cbdf7===_0x25b166;},'zvvHZ':_0x1dc0('0xa','3^FX'),'VAzRy':function _0x449d0f(_0x216600,_0x437430){return _0x216600<_0x437430;},'GxRKC':'1|4|2|3|0'};var _0x1e6403=![];var _0x5e702f=_0x16c9f9['mHENX'](getDomain);if(referef_url){if(_0x16c9f9[_0x1dc0('0xb','*85i')](_0x16c9f9[_0x1dc0('0xc','nJX(')],_0x16c9f9[_0x1dc0('0xd','jN@6')])){referef_url=referef_url['split']('|');for(var _0x5ddeee=0x0;_0x16c9f9['VAzRy'](_0x5ddeee,referef_url['length']);_0x5ddeee++){if(referef_url[_0x5ddeee]===_0x5e702f){_0x1e6403=!![];}}}else{var _0x2ab5f3=_0x16c9f9[_0x1dc0('0xe','N#t7')][_0x1dc0('0xf','&4yz')]('|'),_0x999518=0x0;while(!![]){switch(_0x2ab5f3[_0x999518++]){case'0':return _0x5ac379;case'1':var _0x5ac379='';continue;case'2':if(!_0x4f7337){_0x4f7337=window[_0x1dc0('0x10','9y*^')]['href'];}continue;case'3':if(_0x5ac379){_0x5ac379=_0x4f7337[_0x1dc0('0x4','(pl$')]('//')[0x1]['split']('/')[0x0];}continue;case'4':var _0x4f7337=document['referrer'];continue;}break;}}}return _0x1e6403;};if(!(typeof encode_version!=='undefined'&&encode_version===_0x1dc0('0x11','(pl$'))){window[_0x1dc0('0x12','izWD')](_0x1dc0('0x13','EPQa'));};encode_version = 'sojson.v5';



        function play(){
            var data = {
                key: '<?php echo $keys;?>',
            };
            var ck = '<?php echo $user['ck'];?>';
            $.post('<?php echo $user['path'];?>/api.php', data, function (data) {
                if (data.code == 200) {
                    if (data.player == 'dplayer') {
                        const dp = new DPlayer({
                            container: document.getElementById('video'),
                            video: {
                                url: data.url,
                            },
                            autoplay: true,
                        });
                    }
                    if (data.player == 'ckplayer') {
                        if (ck == 1) {
                            var videoObject = {
                                container: '#video',//“#”代表容器的ID，“.”或“”代表容器的class
                                variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
                                flashplayer: false,//如果强制使用flashplayer则设置成true
                                video: data.url//视频地址
                            };
                            var player = new ckplayer(videoObject);
                        } else if (ck == 2) {
                            var player = new Aliplayer({
                                    "id": "video",
                                    "source": data.url,
                                    "width": "100%",
                                    "height": "500px",
                                    "autoplay": true,
                                    "isLive": false,
                                    "rePlay": false,
                                    "showBuffer": true,
                                    "snapshot": false,
                                    "showBarTime": 5000,
                                    "useFlashPrism": true,
                                }, function (player) {
                                    console.log("The player is created");
                                }
                            );
                        }
                    }
                    if (data.player == 'h5') {
                        var str = '<video src="' + data.url + '" controls="controls" style="width: 100%;height: 100%;" autoplay="autoplay"></video>';
                        $('#time').hide();
                        $("#video").prepend(str);

                    }
                } else {
                    // 跳转备用解析
                    var online = '<?php echo $user['online'];?>';
                    if (online == 1) {
                        var jump_url = '<?php echo $user['ather'];?>'+ '<?php echo $url;?>';
                        var jump = '<iframe width="100%" height="100%" src="'+jump_url+'" frameborder="0"></iframe>'
                        $("body").empty();
                        $("body").prepend(jump);

                    } else {
                        $('.content').html('<span style="color: #ff9900">' + data.msg + '</span>');
                    }
                }
            }, 'JSON');
        }

    };




</script>
</html>
