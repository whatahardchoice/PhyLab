查找

```regex
%%(.+?)%%
```

将所有的纯文字部分使用如下表达式替换

```regex
<span class="desexp-span"><text class="desexp-text">$1</text></span>
```



现在查找`%%(.+?)%%`应只剩下公式

首先检查一下%%和公式开头（span标签）之间有没有空格，去掉空格

然后用

```
%%<span class="MathJax_SVG"
```

查找所有公式

用

```
%%<span class="MathJax_SVG desexp-text"
```

替换

再查找

```regex
%%(.+?)%%
```

用如下表达式替换

```regex
<span class="desexp-span">$1</span>
```



然后在`<head>`最后增加

```html
<link href="/css/desexp-html.css" rel="stylesheet" type="text/css">
```

在body最后增加

```html
<script>
    var inners = document.getElementsByClassName("desexp-text");
    var myfunction = function() {
        if (this.style.opacity == 1) this.style.opacity = "";
        else this.style.opacity = 1;
    };
    for (var i = 0; i < inners.length; i++) {
        inners[i].addEventListener("click", myfunction, false);
        inners[i].addEventListener('touchstart', function (e) {
            e.preventDefault();
            if (this.style.opacity == 1) this.style.opacity = "";
            else this.style.opacity = 1;
        }, false);
    }
</script>
```

