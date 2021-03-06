<?php
function quadrants($p, $queries) {
    // Write your code here
    $n = count($p);
    //求原始象限数组
    $originalArr = [];
    for ($i = 0; $i < $n; $i++) {
        $originalArr[$i + 1] = getQuadrant($p[$i]);
    }
    
    // 线段树
    $segTree = [];
    // 线段树lazy更新标记
    $lazyFlag = [];
    
    // 创建线段树
    buildSegTree($segTree, $lazyFlag, $originalArr, 1, 1, $n);
    
    // 处理每个查询
    $str = "";
    foreach ($queries as $query) {
        $item = explode(" ", $query);
        if ($item[0] == 'X') { // 沿X轴翻转
            updateSegTree($segTree, $lazyFlag, $item[1], $item[2], 1, 1, 1, $n);
        } else if ($item[0] == 'Y') { // 沿Y轴翻转
            updateSegTree($segTree, $lazyFlag, $item[1], $item[2], 2, 1, 1, $n);
        } else if ($item[0] == 'C') { // 统计区间内数量
            $result = getSegTreeRange($segTree, $lazyFlag, $item[1], $item[2], 1, 1, $n);
            $str .= implode(" ", $result) . "\n";
        }
    }
    
    echo $str;
    //file_put_contents("./quadrants_out.txt", $str);
}

function getSegTreeRange(&$segTree, &$lazyFlag, $rangeLeft, $rangeRight, $root, $left, $right)
{
    // 如果整个包含在区间内， 直接返回
    if ($left >= $rangeLeft && $right <= $rangeRight) {
        return $segTree[$root];
    }
    
    // 查询前，先下推现有flag
    pushDown($segTree, $lazyFlag, $root);
    
    $result = [0, 0, 0, 0];
    
    // 继续查询左右子区间
    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex | 1;

    if ($mid >= $rangeLeft) {
        $tempResult = getSegTreeRange($segTree, $lazyFlag, $rangeLeft, $rangeRight, $leftIndex, $left, $mid);
        for ($i = 0; $i < 4; $i++) {
            $result[$i] += $tempResult[$i];
        }
    }
    
    if ($mid < $rangeRight) {
        $tempResult = getSegTreeRange($segTree, $lazyFlag, $rangeLeft, $rangeRight, $rightIndex, $mid + 1, $right);
        for ($i = 0; $i < 4; $i++) {
            $result[$i] += $tempResult[$i];
        }
    }
    
    return $result;
}

function updateSegTree(&$segTree, &$lazyFlag, $rangeLeft, $rangeRight, $updateType, $root, $left, $right)
{
    // 如果整个包含在区间内， 设置flag， 更新当前值
    if ($left >= $rangeLeft && $right <= $rangeRight) {
        $lazyFlag[$root] = $lazyFlag[$root] ^ $updateType;
        changeItem($segTree, $root, $updateType);
        return;
    }
    
    // 先下推现有flag
    pushDown($segTree, $lazyFlag, $root);
    
    // 继续处理左右子区间
    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex | 1;
    // 继续递归处理左右区间
    if ($mid >= $rangeLeft) {
        updateSegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $updateType, $leftIndex, $left, $mid);
    }
    if ($mid < $rangeRight) {
        updateSegTree($segTree, $lazyFlag, $rangeLeft, $rangeRight, $updateType, $rightIndex, $mid + 1, $right);
    }
    
    // 上推更新
    pushUp($segTree, $root);
}

function pushDown(&$segTree, &$lazyFlag, $root)
{
    // 没有flag， 什么也不用做
    if ($lazyFlag[$root] == 0) {
        return;
    }
    
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex | 1;
    
    $updateType = $lazyFlag[$root];
    // 标记下放到左右区间
    changeItem($segTree, $leftIndex, $updateType);
    $lazyFlag[$leftIndex] = $lazyFlag[$leftIndex] ^ $updateType;
    changeItem($segTree, $rightIndex, $updateType);
    $lazyFlag[$rightIndex] = $lazyFlag[$rightIndex] ^ $updateType;
    
    $lazyFlag[$root] = 0;
}

function changeItem(&$segTree, $root, $updateType)
{
    switch ($updateType) {
        case 1:
            $temp = $segTree[$root][1];
            $segTree[$root][1] = $segTree[$root][2];
            $segTree[$root][2] = $temp;

            $temp = $segTree[$root][0];
            $segTree[$root][0] = $segTree[$root][3];
            $segTree[$root][3] = $temp;
            break;
        case 2:
            $temp = $segTree[$root][0];
            $segTree[$root][0] = $segTree[$root][1];
            $segTree[$root][1] = $temp;

            $temp = $segTree[$root][2];
            $segTree[$root][2] = $segTree[$root][3];
            $segTree[$root][3] = $temp;
            break;
        case 3:
            $temp = $segTree[$root][0];
            $segTree[$root][0] = $segTree[$root][2];
            $segTree[$root][2] = $temp;

            $temp = $segTree[$root][1];
            $segTree[$root][1] = $segTree[$root][3];
            $segTree[$root][3] = $temp;
            break;
    }
}

function buildSegTree(&$segTree, &$lazyFlag, &$originalArr, $root, $left, $right)
{
    if ($left == $right) { //叶节点
        $segTree[$root] = [0, 0, 0, 0];
        $segTree[$root][$originalArr[$left]] = 1;
        $lazyFlag[$root] = 0;
        return;
    }
    
    $mid = intval(($left + $right)/2);
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex | 1;
    // 继续递归处理左右区间
    buildSegTree($segTree, $lazyFlag, $originalArr, $leftIndex, $left, $mid);
    buildSegTree($segTree, $lazyFlag, $originalArr, $rightIndex, $mid + 1, $right);

    for ($i = 0; $i < 4; $i++) {
        $segTree[$root][$i] = $segTree[$leftIndex][$i] + $segTree[$rightIndex][$i];
    }
    $lazyFlag[$root] = 0;
}

// 上推求和
function pushUp(&$segTree, $root)
{
    $leftIndex = $root << 1;
    $rightIndex = $leftIndex | 1;
    
    for ($i = 0; $i < 4; $i++) {
        $segTree[$root][$i] = $segTree[$leftIndex][$i] + $segTree[$rightIndex][$i];
    }
}

// 获得象限
function getQuadrant($point)
{
    if ($point[0] > 0) {
        if ($point[1] > 0) { // 1象限
            return 0;
        } else { // 4象限
            return 3;
        }
    } else {
        if ($point[1] > 0) { // 2象限
            return 1;
        } else { // 3象限
            return 2;
        }
    }
}
