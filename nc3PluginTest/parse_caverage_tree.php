<?php
$plugin = $argv[1];
$displayChildren = (int)$argv[2];
define('COVERAGE_DIR', '/var/www/app/app/webroot/coverage/');
define('PLUGIN_DIR', '/var/www/app/app/Plugin/');

$messages = '';
$fileCount = 0;

function output_caverage($dirName, $indent, $displayUrl) {
	global $plugin, $messages, $fileCount, $displayChildren;
	
	$dir = dir(PLUGIN_DIR . $dirName);
	
	$outputs = array();
	if ($dirName === $plugin) {
		$shellFileName = $dirName . '.html';
	} else {
		$shellFileName = strtr(substr($dirName, strlen($plugin) + 1), '/', '_') . '.html';
	}
	if ($displayChildren || $dirName === $plugin) {
		exec('php ' . __DIR__ . '/parse_caverage.php ' . $plugin . ' ' . $shellFileName . ' ' . $indent . ' ' . (int)$displayUrl, $outputs);
		if ($outputs) {
			if ($dirName !== $plugin) {
				$messages .= "\n";
			}
			foreach ($outputs as $output) {
				$messages .= $output . "\n";
			}
		}
	}

	while (false !== ($fileName = $dir->read())) {
		if (! in_array($fileName, ['.', '..', '.git', 'Test', 'TestSuite'], true) && 
				! is_file(PLUGIN_DIR . $dirName . '/' . $fileName)) {

			output_caverage($dirName . '/' . $fileName, $indent + 4, false);
		}
		if (in_array(substr($fileName, -3), ['ctp', 'php'], true) && 
				is_file(PLUGIN_DIR . $dirName . '/' . $fileName)) {
		
			$fileCount++;
		}
	}
	$dir->close();
}

output_caverage($plugin, 0, true);

echo $messages;
echo "\n";
echo '+-----------------------+' . "\n";
echo '+ Total: ' . $fileCount . ' Files ' . str_pad(' ', 7 - strlen((string)$fileCount)) . ' +' . "\n";
echo '+-----------------------+' . "\n";
echo "\n";
