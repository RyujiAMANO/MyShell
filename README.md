# MyShell

## 環境の再構築シェル（ただし、vagrant環境は構築していること）
※/var/www/app以下のソースを最新化するシェル

<pre>
cd /var/www/MyShell/install
bash install.sh
</pre>

#### ≪第一引数≫
##### develop
<pre>
  中島が開発中の環境
</pre>
##### mathjax
<pre>
  develop＋数式MathJaxも含めた環境
</pre>
##### origin（省略可）
<pre>
  NC3コアにコミットされている純粋な環境
</pre>
#### ≪第二引数≫
##### docs
<pre>
  NetCommons3Docsも含めて最新にする
</pre>
##### それ以外（省略可）
<pre>
  NetCommons3Docsは最新にしない
</pre>
