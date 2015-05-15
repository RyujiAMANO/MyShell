# テストシェル

## nc3PluginTest
（小まめに最新を取得してね）

<pre>
cd /var/www/MyShell/nc3PluginTest
bash nc3PluginTest.sh プラグイン名(キャメル記法)
</pre>

### ≪第一引数≫

プラグイン名(キャメル記法)

(例)bash nc3PluginTest.git AccessCounters

### ≪第二引数≫

第二引数に「phpcs」「phpmd」「phpcpd」「gjslint」「phpunit」を付けることで、個別にテストを実行することができる。

| パラメータ         | 説明                    |
| ------------------ | ----------------------- |
| phpcs              | PHP CodeSniffer         |
| phpmd              | PHP Mess Detector       |
| phpcpd             | PHP Copy/Paste Detector |
| gjslint            | JavaScript Style Check  |
| phpunit            | PHP UnitTest            |
| phpdoc             | PHP Mess Detector       |
| phpmd              | PHP Documentor(phpdoc)  |
| pear_install       | Pear等に関する最新化    |



