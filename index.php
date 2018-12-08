<?php
function buildTree(&$data, &$idx, &$id) {
	$currentId = $id++;
	$numberOfChildren = $data[$idx++];
	$numberOfMetadataEntries = $data[$idx++];
	$children = [];
	$metadataEntries = [];
	for ($i = 0; $i < $numberOfChildren; ++$i) {
		array_push($children, buildTree($data, $idx, $id));
	}
	for ($i = 0; $i < $numberOfMetadataEntries; ++$i) {
		array_push($metadataEntries, $data[$idx++]);
	}
	return [$numberOfChildren, $numberOfMetadataEntries, $children, $metadataEntries, $currentId];
}
$id = 0;
$idx = 0;
$data = explode(' ', trim(file_get_contents(__DIR__ . '/input.txt')));
$tree = buildTree($data, $idx, $id);
// the first puzzle
function sumMetadataEntries($node) {
	$sum = array_sum($node[3]);
	foreach ($node[2] as $child) {
		$sum += sumMetadataEntries($child);
	}
	return $sum;
}
echo sumMetadataEntries($tree) . PHP_EOL;
// the second puzzle
function doComplicatedSum($node, &$cache) {
	$sum = 0;
	if (count($node[2])) {
		foreach ($node[3] as $reference) {
			if (isset($node[2][$reference - 1])) {
				if (isset($cache[$node[2][$reference - 1][4]])) {
					$sum += $cache[$node[2][$reference - 1][4]];
				} else {
					$sum += doComplicatedSum($node[2][$reference - 1], $cache);
				}
			}
		}
	} else {
		$sum = array_sum($node[3]);
	}
	$cache[$node[4]] = $sum;
	return $sum;
}
$cache = [];
echo doComplicatedSum($tree, $cache) . PHP_EOL;
