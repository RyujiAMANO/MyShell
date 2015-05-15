# MyShell

## 環境の再構築シェル（ただし、vagrant環境は構築していること）
※/var/www/app以下のソースを最新化するシェル

<pre>
cd /var/www/MyShell/install
bash install.sh
</pre>

### ≪第一引数≫
#### develop
中島が開発中の環境
#### mathjax
develop＋数式MathJaxも含めた環境
#### origin（省略可）
NC3コアにコミットされている純粋な環境
### ≪第二引数≫
#### docs
NetCommons3Docsも含めて最新にする
#### それ以外（省略可）
NetCommons3Docsは最新にしない
