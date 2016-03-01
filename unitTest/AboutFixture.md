# Fixtureについて

基本的には、下記の構成でテストを行います。そのため、Fixtureも下記の内容を用意して下さい。

### コンテンツのFixture
````
// * ルーム管理者が書いたコンテンツ
array(
	'id' => '1',
	'block_id' => '2',
	'key' => 'content_key_1',
	'language_id' => '2',
	'status' => '1',
	'is_active' => true,
	'is_latest' => false,
	//TODO:その他のフィールドデータ
	'created_user' => '1'
),
array(
	'id' => '2',
	'block_id' => '2',
	'key' => 'content_key_1',
	'language_id' => '2',
	'status' => '4',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '1'
),
// * 一般が書いたコンテンツ＆一度も公開していない
array(
	'id' => '3',
	'block_id' => '2',
	'key' => 'content_key_2',
	'language_id' => '2',
	'status' => '3',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '4'
),
// * 一般が書いたコンテンツ＆公開している
array(
	'id' => '4',
	'block_id' => '2',
	'key' => 'content_key_3',
	'language_id' => '2',
	'status' => '1',
	'is_active' => true,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '4'
),
// * 一般が書いたコンテンツ＆一度公開して、下書き中
array(
	'id' => '5',
	'block_id' => '2',
	'key' => 'content_key_4',
	'language_id' => '2',
	'category_id' => '1',
	'status' => '1',
	'is_active' => true,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '4'
),
array(
	'id' => '6',
	'block_id' => '2',
	'key' => 'content_key_4',
	'language_id' => '2',
	'status' => '3',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '4'
),
// * 編集長が書いたコンテンツ＆一度も公開していない
array(
	'id' => '7',
	'block_id' => '2',
	'key' => 'content_key_5',
	'language_id' => '2',
	'status' => '3',
	'is_active' => false,
	'is_latest' => true,
	//TODO:その他のフィールドデータ
	'created_user' => '3'
),
````


### ブロックIDの紐付くFixture
````
array(
	'id' => '2',
	'block_id' => '2',
	'key' => 'content_block_1',
	//TODO:その他のフィールド追記
),
array(
	'id' => '4',
	'block_id' => '4',
	'key' => 'content_block_2',
	//TODO:その他のフィールド追記
),
````
その他、使用できるBlockデータは、<a href="https://github.com/NetCommons3/Blocks/blob/master/Test/Fixture/BlockFixture.php#L56-L178">こちら</a>を参考に設定して下さい。


### ブロックKeyに紐付くFixture
````
array(
	'id' => '1',
	'block_key' => 'block_1',
	//TODO:その他のフィールド追記
),
array(
	'id' => '2',
	'block_key' => 'block_2',
	//TODO:その他のフィールド追記
),
````
その他、使用できるBlockデータは、<a href="https://github.com/NetCommons3/Blocks/blob/master/Test/Fixture/BlockFixture.php#L56-L178">こちら</a>を参考に設定して下さい。


### フレームIDに紐付くFixture

基本的には、フレームIDと紐付くテーブルは無いはずですが、もしFixtureを生成する場合、

<u>frame_id = '6', '16', '18'</u>を除いて作成して下さい。


### フレームKeyに紐付くFixture
````
array(
	'id' => '6',
	'frame_key' => 'frame_3',
	//TODO:その他のフィールド追記
),
````
その他、使用できるFrameデータは、<a href="https://github.com/NetCommons3/Frames/blob/master/Test/Fixture/FrameFixture.php#L47-L178">こちら</a>を参考に設定して下さい。
