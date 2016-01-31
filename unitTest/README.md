# UnitTestファイル作成シェル

## unitTest

<pre>
cd /var/www/MyShell/unitTest
bash startUnitTest.sh プラグイン名(キャメル記法)
</pre>

### ≪第一引数≫

プラグイン名(キャメル記法)

(例)bash startUnitTest.sh AccessCounters


### ≪第二引数≫

タイプ

| パラメータ          | 説明                    |
| ------------------- | ----------------------- |
| Controller          | Controllerのテストファイルを作成する |
| ControllerComponent | Controller/Componentのテストファイルを作成する |
| Model               | Modelのテストファイルを作成する |
| ModelBehavior       | Model/Behaviorのテストファイルを作成する |
| ViewElements        | View/Elementsのテストファイルを作成する |
| ViewHelper          | View/Helperのテストファイルを作成する |
| Other               | その他のテストファイルを作成する |
| All or 省略         | 全ファイルのテストファイルを作成する |

(例)bash startUnitTest.sh AccessCounters Model


### ≪第三引数≫

ファイル
※省略すると、すべてのファイルが対象となる

(例)bash startUnitTest.sh AccessCounters Model AccessCounter



### ≪第四引数≫

メソッド
※省略すると、すべてのメソッドが対象となる

(例)bash startUnitTest.sh AccessCounters Model AccessCounter saveAccessCounter

