<?php
$plugin = $argv[1];
$fileName = $argv[2];
$nest = $argv[3];
define('PAD_SPACE_LEN', 21);

if (! $file = file_get_contents('/var/www/app/app/webroot/coverage/' . $plugin . '/' . $fileName)) {
	if (! $file = file_get_contents('/var/www/app/app/webroot/coverage/' . $plugin . '/' . 'app_' . $fileName)) {
		exit;
	}
	$fileName = 'app_' . $fileName;
}

function html_truncate($html) {
	$html = strip_tags($html);

	$html = preg_replace("/[ ]{2,}/ius", "", $html);

	$html = preg_replace('/&nbsp;' . preg_quote('/', '/') . '&nbsp;/iu', '/', $html);
	$html = preg_replace("/&nbsp;\n\n&nbsp;/iu", '-.--% (-/-)', $html);

	$html = preg_replace('/&nbsp;/iu', '', $html);
	$html = preg_replace("/\n+/ius", "\n", $html);

	$html = preg_replace("/\n([0-9]+)" . preg_quote('/', '/') . "([0-9]+)/ius", " (\\1/\\2)", $html);
	$html = preg_replace("/\n([0-9]+)/ius", "    \\1", $html);
	$html = preg_replace("/\n-/ius", "    -", $html);

	return trim($html);
}

/**
 * 各ファイルのカバレッジ
 */
$contents = preg_replace('/<title.+title>/iUus', '', $file);
$contents = preg_replace('/<header.+header>/iUus', '', $contents);
$contents = preg_replace('/<footer.+footer>/iUus', '', $contents);
$contents = preg_replace('/<thead.+thead>/iUus', '', $contents);

$contents = html_truncate($contents);

$hashFile = explode("\n", $contents);
$padSpaceLength = PAD_SPACE_LEN;
foreach ($hashFile as $i => $value) {
	$hashFile2 = explode('    ', $value);
	if ($padSpaceLength < strlen($hashFile2[0])) {
		$padSpaceLength = strlen($hashFile2[0]) + 1;
	}
}

foreach ($hashFile as $i => $value) {
	$hashFile2 = explode('    ', $value);
	foreach ($hashFile2 as $j => $value2) {
		if ($j === 0) {
			$hashFile2[$j] = str_pad('', $nest) . '| ' . str_pad($value2, $padSpaceLength) . ' ';
		} else {
			$hashFile2[$j] = '| ' . str_pad($value2, PAD_SPACE_LEN) . ' ';
		}
	}
	$hashFile[$i] = implode('', $hashFile2);
}
$contents = implode(" |\n", $hashFile) . ' ';


/**
 * レポートのタイトル
 */
$matches = array();
$title = preg_match('/<title.+title>/iUus', $file, $matches);
$title = $matches[0];
$title = html_truncate($title);

/**
 * ヘッダー及びフッター
 */
$matches = array();
$head = preg_match('/<thead.+thead>/iUus', $file, $matches);
$head = $matches[0];
$head = html_truncate($head);

$hashHead = explode("\n", $head);
$headValue1 = '';
$headValue2 = '';
$headValue3 = '';

foreach ($hashHead as $i => $value) {
	if ($i === 0) {
		$headValue1 .= str_pad('', $nest) . '+-' . str_pad('', $padSpaceLength, '-') . '-';
		$headValue2 .= str_pad('', $nest) . '| ' . str_pad($value, $padSpaceLength) . ' ';
		$headValue3 .= str_pad('', $nest) . '+-' . str_pad('', $padSpaceLength, '-') . '-';
	} else {
		$headValue1 .= '+-' . str_pad('', PAD_SPACE_LEN, '-') . '-';
		$headValue2 .= '| ' . str_pad($value, PAD_SPACE_LEN) . ' ';
		$headValue3 .= '+-' . str_pad('', PAD_SPACE_LEN, '-') . '-';
	}
}
$head = $headValue1 . "-+\n" . $headValue2 . " |\n" . $headValue3 . "-+";
$footValue = $headValue1;

/**
 * 結果の出力
 */
echo str_pad('', $nest) . $title . "\n" . $head . "\n" . $contents . "|\n" . $footValue . "-+" . "\n\n";
