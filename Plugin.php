<?php 
/**
 * 在网站底部插入APlayer吸底播放器<br/>开源项目：<a href="https://github.com/DIYgod/APlayer" target="_blank">APlayer</a> & <a href="https://github.com/metowolf/MetingJS" target="_blank">MetingJS</a>
 * 
 * @package APlayerAtBottom
 * @author 小太
 * @version 1.0.0
 * @link https://713.moe/
 */
class APlayerAtBottom_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate(){
        Typecho_Plugin::factory('Widget_Archive')->footer = array('APlayerAtBottom_Plugin', 'footer');
        Typecho_Plugin::factory('Widget_Archive') ->header = array('APlayerAtBottom_Plugin', 'header');
    	return'启用成功ヾ(≧▽≦*)o，请设置您您的歌单ID~';
    }
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
    	return'禁用成功！插件已经停用啦（；´д｀）ゞ';
    }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
    	$id = new Typecho_Widget_Helper_Form_Element_Text('id', null, '2105681544', _t('歌单id'), '这里填写你的 <b>网易云音乐</b> 歌单id（目前仅支持网易云音乐）');
        $form->addInput($id);
      	$autoplay = new Typecho_Widget_Helper_Form_Element_Text('autoplay', null, 'false', _t('自动播放'), '填写true则打开页面后自动播放，填写false则打开页面后不自动播放<br/>PS：部分主题或浏览器可能不支持此项。');
        $form->addInput($autoplay);
        $theme = new Typecho_Widget_Helper_Form_Element_Text('theme', null, '#3498db', _t('主题颜色'), '这里填写十六进制颜色代码，作为进度条和音量条的主题颜色');
        $form->addInput($theme);
        $volume = new Typecho_Widget_Helper_Form_Element_Text('volume', null, '0.7', _t('默认音量'), '这里填写不大于1的数字作为默认音量<br/>PS：播放器会记忆用户设置，用户手动设置音量后默认音量即失效');
        $form->addInput($volume);
      	$lrc = new Typecho_Widget_Helper_Form_Element_Text('lrc', null, 'true', _t('是否开启歌词'), '填写true则开启歌词，填写false则关闭歌词');
        $form->addInput($lrc);
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render(){}
    public static function header(){
    	echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/aplayer@1.10.0/dist/APlayer.min.css">';
    }
    public static function footer(){
     $config = Typecho_Widget::widget('Widget_Options')->plugin('APlayerAtBottom');
        $id = Typecho_Widget::widget('Widget_Options') -> Plugin('APlayerAtBottom') -> id;
     	$autoplay = Typecho_Widget::widget('Widget_Options') -> Plugin('APlayerAtBottom') -> autoplay;
      	$theme = Typecho_Widget::widget('Widget_Options') -> Plugin('APlayerAtBottom') -> theme;
      	$volume = Typecho_Widget::widget('Widget_Options') -> Plugin('APlayerAtBottom') -> volume;
      	$lrc = Typecho_Widget::widget('Widget_Options') -> Plugin('APlayerAtBottom') -> lrc;
      
      	if($lrc === 'true') {
        	$lrc_out = 3;
        }else{
        	$lrc_out = 0;
        }
      	
        echo '<div id="downplayer"></div>
        	<script src="//cdn.jsdelivr.net/npm/aplayer@1.10.0/dist/APlayer.min.js"></script>
			<script type="text/javascript">
              $(function(){
                  $.get("https://api.i-meto.com/meting/api?server=netease&type=playlist&id='.$id.'", function(result){
                      var audio = $.parseJSON(result);
                      const ap = new APlayer({
                          container: document.getElementById(\'downplayer\'),
                          lrcType: '.$lrc_out.',
                          autoplay: '.$autoplay.',
                          fixed: true,
                          theme: \''.$theme.'\',
                          volume: '.$volume.',
                          audio: audio
                      });
                  });
              });
          </script>';
    }
}
?>