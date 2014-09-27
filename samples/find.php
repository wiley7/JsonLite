<?php
/**
 * find.php
 * 2014-09-27
 *
 * Developed by yewei <yewei@playcrab.com>
 * Copyright (c) 2014 Playcrab Corp.
 *
 * Desc:
 */

include __DIR__."/../JsonLite.php";

$dataFile = __DIR__."/data.log";

$lite = new JsonLite($dataFile);

$ret = $lite->find(array('a'=>2));
echo json_encode($ret) . "\n";

$ret = $lite->find(array('a'=>2, 'b'=>2), array('c'=>0));
echo json_encode($ret) . "\n";

$ret = $lite->find(array('a'=>array('$gt'=>1)));
echo json_encode($ret) . "\n";

$ret = $lite->find(array('a'=>array('d'=>array('$gt'=>1))));
echo json_encode($ret) . "\n";
