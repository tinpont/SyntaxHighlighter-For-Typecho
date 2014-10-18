<?php

/**
 * Syntax Highlighter for Typecho
 * 
 * @package Syntax Highlighter
 * @author Tinpont
 * @version 0.0.2
 * @link http://www.tinpont.com
 */
class SyntaxHighlighter_Plugin implements Typecho_Plugin_Interface {

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array('SyntaxHighlighter_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('SyntaxHighlighter_Plugin', 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate() {
        
    }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {
        $theme = new Typecho_Widget_Helper_Form_Element_Select('theme', array('Default' => 'Default',
            'Django' => 'Django',
            'Eclipse' => 'Eclipse',
            'Emacs' => 'Emacs',
            'FadeToGrey' => 'FadeToGrey',
            'MDUltra' => 'MDUltra',
            'Midnight' => 'Midnight',
            'RDark' => 'RDark'), 'Default', _t('高亮主题:'), _t('选择一个你喜欢的高亮主题。'));
        $form->addInput($theme);

        $collapse = new Typecho_Widget_Helper_Form_Element_Checkbox('collapse', array('collapse' => '折叠代码'), NULL, _t('代码折叠'), _t('是否自动折叠代码，点击时展开（开启时，请同时开启显示工具栏，不然代码无法显示）'));
        $form->addInput($collapse);

        $codeFormat = new Typecho_Widget_Helper_Form_Element_Checkbox('codeFormat', array('gutter' => '显示行号',
            'auto-links' => '链接关键字文档',
            'smart-tabs' => '智能缩进'
                ), array('gutter',
            'auto-links'
                ), _t('格式设置'), _t('默认显示行号、自动链接关键字文档、关闭智能缩进。'));
        $form->addInput($codeFormat);

        $tabSize = new Typecho_Widget_Helper_Form_Element_Text('tabSize', NULL, 4, _t('<TAB>缩进宽度'), _t('输入代码<TAB>缩进时占几个空格的宽度，建议2、4、8等值，默认占4个空格。'));
        $form->addInput($tabSize);

        $toolbar = new Typecho_Widget_Helper_Form_Element_Checkbox('toolbar', array('toolbar' => '显示工具栏'), NULL, _t('工具栏设置'), _t('设置是否显示代码块右上角的工具栏，默认不显示。'));
        $form->addInput($toolbar);
        //$codeCollapse = new 
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form) {
        
    }

    /**
     * 输出头部js和css
     *
     * @access public
     * @param unknown $header
     * @return unknown
     */
    public static function header() {
        $settings = Helper::options()->plugin('SyntaxHighlighter');
        $currentPath = Helper::options()->pluginUrl . '/SyntaxHighlighter/';

        echo '<script type="text/javascript" src="' . $currentPath . 'scripts/shCore.min.js"></script>' . "\n";
        echo '<script type="text/javascript" src="' . $currentPath . 'scripts/shAutoloader.js"></script>' . "\n";
        echo '<link rel="stylesheet" type="text/css" href="' . $currentPath . 'styles/shCore' . $settings->theme . '.css" />' . "\n";
    }

    /**
     * 输出尾部js
     *
     * @access public
     * @param unknown $footer
     * @return unknown
     */
    public static function footer() {
        $settings = Helper::options()->plugin('SyntaxHighlighter');

        $collapse = 'false';
        if ($settings->collapse && in_array('collapse', $settings->collapse))
            $collapse = 'true';

        $gutter = 'false';
        if ($settings->codeFormat && in_array('gutter', $settings->codeFormat))
            $gutter = 'true';

        $autoLinks = 'false';
        if ($settings->codeFormat && in_array('auto-links', $settings->codeFormat))
            $autoLinks = 'true';

        $smartTabs = 'false';
        if ($settings->codeFormat && in_array('smart-tabs', $settings->codeFormat))
            $smartTabs = 'true';

        $toolbar = 'false';
        if ($settings->toolbar && in_array('toolbar', $settings->toolbar))
            $toolbar = 'true';

        $tabSize = $settings->tabSize;

        $currentPath = Helper::options()->pluginUrl . '/SyntaxHighlighter/';

        echo <<<EOF
        <script type="text/javascript">
            if (typeof(SyntaxHighlighter) !== undefined) {
                var preList = document.getElementsByTagName('pre');
                for (var i = 0; i < preList.length; i ++) {
                    var children = preList[i].getElementsByTagName('code');
                    if (children.length > 0) {
                        var language = 'plain';
                        var code = children[0], className = code.className;
                        if (!!className) {
                            var match = XRegExp.exec(className, XRegExp('^(lang|language)-(?<language>.*)$'));
                            if (match && match.language) {
                                language = match.language;
                            }
                        }
                        preList[i].className = 'brush: ' + language;
                        preList[i].innerHTML = code.innerHTML;
                    }
                }
                SyntaxHighlighter.autoloader(
                        'applescript           {$currentPath}scripts/shBrushAppleScript.js',
                        'ahk autohotkey        {$currentPath}scripts/shBrushAhk.js',
                        'actionscript3 as3     {$currentPath}scripts/shBrushAS3.js',
                        'bash shell            {$currentPath}scripts/shBrushBash.js',
                        'bat cmd batch         {$currentPath}scripts/shBrushBat.js',
                        'coldfusion cf         {$currentPath}scripts/shBrushColdFusion.js',
                        'cpp c                 {$currentPath}scripts/shBrushCpp.js',
                        'c# c-sharp csharp     {$currentPath}scripts/shBrushCSharp.js',
                        'css                   {$currentPath}scripts/shBrushCss.js',
                        'delphi pascal pas     {$currentPath}scripts/shBrushDelphi.js',
                        'diff patch            {$currentPath}scripts/shBrushDiff.js',
                        'erl erlang            {$currentPath}scripts/shBrushErlang.js',
                        'groovy                {$currentPath}scripts/shBrushGroovy.js',
                        'java                  {$currentPath}scripts/shBrushJava.js',
                        'jfx javafx            {$currentPath}scripts/shBrushJavaFX.js',
                        'js jscript javascript {$currentPath}scripts/shBrushJScript.js',
                        'perl pl               {$currentPath}scripts/shBrushPerl.js',
                        'php                   {$currentPath}scripts/shBrushPhp.js',
                        'text plain            {$currentPath}scripts/shBrushPlain.js',
                        'powershell ps         {$currentPath}scripts/shBrushPowerShell.js',
                        'py python             {$currentPath}scripts/shBrushPython.js',
                        'ruby rails ror rb     {$currentPath}scripts/shBrushRuby.js',
                        'sass scss             {$currentPath}scripts/shBrushSass.js',
                        'scala                 {$currentPath}scripts/shBrushScala.js',
                        'sql                   {$currentPath}scripts/shBrushSql.js',
                        'vb vbnet              {$currentPath}scripts/shBrushVb.js',
                        'xml xhtml xslt html   {$currentPath}scripts/shBrushXml.js'
                        );
                SyntaxHighlighter.defaults['auto-links'] = $autoLinks;
                SyntaxHighlighter.defaults['collapse'] = $collapse;
                SyntaxHighlighter.defaults['gutter'] = $gutter;
                SyntaxHighlighter.defaults['smart-tabs'] = $smartTabs;
                SyntaxHighlighter.defaults['tab-size'] = $tabSize;
                SyntaxHighlighter.defaults['toolbar'] = $toolbar;
                SyntaxHighlighter.all();
            }
        </script>
EOF;
        echo "\n";
    }

}

