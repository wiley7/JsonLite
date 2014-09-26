JsonLite
========

## 日志文件
每行都是一个json字符串

    {"a":1,"b":1,"c":2}
    {"a":1,"b":1,"c":1}
    {"a":2,"b":1,"c":1}
    {"a":1,"b":1,"c":1}
    {"a":1,"b":3,"c":1}
    {"a":1,"b":1,"c":1}
    {"a":2,"b":2,"c":1}

## 使用

初始化

    include __DIR__."/../JsonLite.php";
    $dataFile = __DIR__."/data.log";
    $lite = new JsonLite($dataFile);

`$dataFile`为数据文件。

### 查询
查询方式尽量向MongoDB中的语句靠齐。

        $ret = $lite->find(array('a'=>2, 'b'=>2));
        echo json_encode($ret) . "\n";
结果为：

        [{"a":2,"b":2,"c":1}]

不等式判断：

        $ret = $lite->find(array('a'=>array('$gt'=>1)));
        echo json_encode($ret) . "\n";
结果为：

        [{"a":2,"b":1,"c":1},{"a":2,"b":2,"c":1}]
