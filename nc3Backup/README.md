# nc3Backup

## バックアップシェル
（ダウンロードしたシェルは中身を見てね）

※tar.gzでローカルにバックアップします。

<pre>
cd /var/www/MyShell/nc3Backup
bash nc3Backup.sh
</pre>

<pre>
cd /var/www/MyShell/nc3Backup
bash nc3Backup.sh all
</pre>


| 引数           | 用途                                  |
| -------------- | ------------------------------------- |
| all            | /var/www/app全てとデータベース(nc3というDB名)をバックアップする |
| なし           | /var/www/app/app/Pluginと/var/www/app/app/Config、データベース(nc3というDB名)のみバックアップする |

