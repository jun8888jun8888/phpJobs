<?php


class Node
{
    private $Data;//节点数据
    private $Next;//存储下个点对象

    public function __construct($data, $next)
    {
        $this->Data = $data;
        $this->Next = $next;
    }

    public function __set($name, $value)
    {
        if (isset($this->$name))
            $this->$name = $value;
    }

    public function __get($name)
    {
        if (isset($this->$name))
            return $this->$name;
        else
            return NULL;
    }
}

class LinkList
{
    private $head;//头节点
    private $len;

    /**
     * 初始化头节点
     */
    public function __construct()
    {
        $this->init();
    }

    public function setHead(Node $val)
    {
        $this->head = $val;
    }

    public function getHead()
    {
        return $this->head;
    }

    public function getLen()
    {
        return $this->len;
    }

    public function init()
    {
        $this->setHead(new Node(NULL, NULL));
        $this->len = 0;
    }

    /**
     * 设置某位置节点的数据
     * @param int $index
     * @param $data
     * @return bool
     */
    public function set(int $index, $data)
    {
        $i = 1;
        $node = $this->getHead();
        while ($node->Next !== NULL && $i <= $index) {
            $node = $node->Next;
            $i++;
        }
        $node->Data = $data;
        return TRUE;
    }

    /**
     * 获取某位置节点的数据
     * @param int $index
     * @return mixed
     */
    public function get(int $index)
    {
        $i = 1;
        $node = $this->getHead();
        while ($node->Next !== NULL && $i <= $index) {
            $node = $node->Next;
            $i++;
        }
        return $node->Data;
    }

    /**
     * 在某位置处插入节点
     * @param $data
     * @param int $index
     * @return bool
     */
    public function insert($data, int $index = 0)
    {
        if ($index <= 0 || $index > $this->getLen())
            return FALSE;
        $i = 1;
        $node = $this->getHead();
        while ($node->Next !== NULL) {
            if ($index === $i) break;
            $node = $node->Next;
            $i++;
        }
        $node->Next = new Node($data, $node->Next);
        $this->len++;
        return TRUE;
    }

    /**
     * 删除某位置的节点
     * @param int $index
     * @return bool
     */
    public function delete(int $index)
    {
        if ($index <= 0 || $index > $this->getLen())
            return FALSE;
        $i = 1;
        $node = $this->getHead();
        while ($node->Next !== NULL) {
            if ($index === $i) break;
            $node = $node->Next;
            $i++;
        }
        $node->Next = $node->Next->Next;
        $this->len--;
        return TRUE;
    }
}
