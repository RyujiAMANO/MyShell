# コンテンツのデータに紐付くFixture
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
