SyntaxHighlighter-For-Typecho
=============================

著名代码高亮插件[SyntaxHighlighter][2] Typecho移植版，同时支持0.8、0.9、1.0

本插件基于[Tolbkni Kao][1]的Syntax Highlighter for Typecho 0.8修改

typecho 0.9新版发布了，就冲这个Markdown也要体验一下。就是因为这个Markdown，以前用的代码高亮插件失效了，同时原作者已经不再使用typecho了，所以只好花一点时间，修改一下原作者的插件。说是修改，其实已经改了大部分内容了。

### 插件目录请命名为 SyntaxHighlighter

typecho 0.9以上版本使用方法：

    ```cpp
    这里填写你的C++代码
    同时上面的cpp也可以换成你想要的语言，比如说javascript
    ```

typecho 0.8及以下使用方法：

    <pre class="brush: html">
    &lt;html&gt;
    &lt;head&gt;这是一个测试标题&lt;/head&gt;
    &lt;body&gt;
        Hello, everyone.
    &lt;/body&gt;
    &lt;/html&gt;
    </pre>

主要原理是通过替换Markdown输出的`<pre><code class="lang-cpp">`为`<pre class="brush:cpp">`从而使Syntax Highlighter能正常渲染。

最后需要注意一个问题，开启代码折叠又没开工具栏的时候，代码会消失，这可能是Syntax Highlighter遗留下来的BUG。所以请开启代码折叠的同时，也把工具栏开启了。

更多的配置选项，请参考[官方网站][3]。

  [1]: https://blog.imtk.me/
  [2]: https://github.com/alexgorbatchev/SyntaxHighlighter
  [3]: http://alexgorbatchev.com/SyntaxHighlighter/manual/configuration/