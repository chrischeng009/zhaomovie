<?php
//-----------------------请修改以下配置------------------------------------
//防盗链模式,0为关闭，1为开启。
define('REFERER_TYPE', 0);
//防盗链域名,即授权域名,填写解析所在域名,多个用|隔开，cdn填写cdn域名 ；
define('REFERER_URL', 'www.user.com|www.u.com');//‘’内填写域名,默认留空不防盗链。
//盗链提示信息,当防盗链模式为1时生效,支持html。
define('REFERER_INFO', '<html><meta name="robots" content="noarchive"><head><title>智能解析系统 - 全网视频在线解析服务</title></head><style>h1{color:#00A0E8; text-align:center; font-family: Microsoft Jhenghei;}p{color:#f90; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style><body bgcolor="#000000"><table width="100%" height="100%" align="center"><td align="center"><h1>接口防盗已开启</h1><p>如需使用，请联系本站管理员进行授权</p></table></body></html>');
//此处进行用户相关配置
$user = array(

    'uid' => '100960', //这里填写你的UID信息,用户授权UID，在 https://www.baores.com 平台可以查看

    'token' => '873ED66FDB8A4BDAF936F4168F8F12BB', //这里填写你的用户密匙信息,用户授权TOKEN，在 https://www.baores.com 平台可以查看

    'auth_code' =>'#uuyucom', //这里填写随机码 用于前端和后端的通信加密

    'path' => '/playvip', //一般不用修改,如果放置为根目录请留空,除非你放置在二级目录,修改格式 'path' => '/jiexi',（jiexi为二级目录名,不要忘记加 /）

    'animation' => '2', //视频加载动画 1,2,3,4  默认为1

    'online' => '0', //当前无法解析的地址是否启动备用解析接口  默认开启,1:开启,0:关闭  注意：开启时要在下面填入备用解析接口,否则无法解析跳转视频官网

    'ather' => 'http://demo.baores.com/?url=', //备用接口设置（尽量不要修改，保证服务器宕机时能及时跳转）

    'title' => '宝思智能解析系统', //设置解析页面title名称   例如：'title' => '诺讯视频解析接口',

    'ck'=>'1',//1为CK播放器  2为阿里播放器  推荐阿里播放器 不过限制域名备案 如果没备案请选择ck播放器

    'api_url' => '43c80tqMbNEBGX7pu22TUtrOHk2GXKuso/OOoAUPHWcDbXlzXG1FHWfJ1cststKvsyf6Sj9Azg', //此为接口与后端通信密钥,禁止修改,修改后会造成无法解析!禁止修改！禁止修改

)
//-----------------------修改区域结束---------------------------------------
?>