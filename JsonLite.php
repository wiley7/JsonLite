<?php
/**
 * JsonLite.php
 * 2014-09-27
 *
 * Developed by yewei <yewei@playcrab.com>
 * Copyright (c) 2014 Playcrab Corp.
 *
 * Desc: 用来查询文件，文件每一行都是一个json格式数据，力求达到mongodb的查询
 */

class JsonLite
{
    public $strictEqual = false;

    private $_dbFile = null;

    public function __construct($file)
    {
        if (empty($file)) {
            throw new JsonLiteException("filename cannot be empty!");
        }
        if (!file_exists($file)) {
            throw new JsonLiteException("filename $file not exists!");
        }
        $this->_dbFile = $file;
    }

    public function getDbFileName()
    {
        return $this->_dbFile;
    }

    /**
     * 查询
     */
    public function find(array $query, array $options = array())
    {
        $result = array();
        $handler = fopen($this->getDbFileName(), 'r');
        while (!feof($handler)) { 
            $line = stream_get_line($handler, 1000000, "\n"); 
            $data = json_decode(trim($line), true);
            if ($data == false) {
                continue;
            }
            if ($this->_judgeData($data, $query)) {
                $result[] = $data;
            }
        } 
        fclose($handler);
        return $result;
    }

    /**
     * 判断某行是否满足需求
     */
    private function _judgeSingle($line, array &$query)
    {
        $data = json_decode($line, true);
        if ($data == false) {
            return false;
        }
        $ret = true;
        foreach($query as $k=>$v) {
            if (is_array($v)) {
                $ret = $this->_judgeData($data[$k], $v);
            } else {
                $ret = $this->_isEqual($data[$k], $v);
            }
            if ($ret == false) {
                return false;
            }
        }
        return $ret;
    }

    /**
     * 检查某个数据
     */
    private function _judgeData($data, array &$query)
    {
        $ret = true;
        foreach($query as $k=>$v) {
            if ($this->_isCmpOpt($k)) {
                $ret = $this->_doCompare($data, $v, $k);
            } elseif (is_array($v)) {
                $ret = $this->_judgeData($data[$k], $v);
            } else {
                $ret = $this->_isEqual($data[$k], $v);
            }
            if ($ret == false) {
                return false;
            }
        }
        return $ret;
    }

    /**
     * 检查是不是一个不等式判断
     */
    private function _isCmpOpt($key)
    {
        return $key[0] == '$';
    }

    /**
     * 判断是否相等
     *
     * @param mix $val
     * @param mix $cmp
     *
     * @return bool
     */
    private function _isEqual($val, $cmp) 
    {
        if ($this->strictEqual) {
            return $val === $cmp;
        }
        return $val == $cmp;
    }

    /**
     * 判断不等式
     * 
     * @param mix $val
     * @param mix $cmp
     * @param str $op 不等式的符号，$gt, $lt, ...
     */
    private function _doCompare($val, $cmp, $op)
    {
        if ($op == '$gt') {
            return $val > $cmp;
        }
        if ($op == '$lt') {
            return $val < $cmp;
        }
    }

    /**
     * 过滤字段
     */
    private function _filter(array &$data, array &$filter)
    {
    }
}

class JsonLiteException extends Exception
{
}
