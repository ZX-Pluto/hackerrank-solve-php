# Real Estate Broker
原题见[这里](https://www.hackerrank.com/challenges/real-estate-broker/problem)

你是房产经纪人，手里有m套房子，每套房子都有面积和价格。

同时，有n位客户，每个人也有期望面积和期望价格。

对于一套房子和一位客户来说，如果房子的面积比客户期望面积要大，同时价格小于等于客户期望价格，则这套房子可以卖给这位客户。

限定条件是一套房子只能卖给一位客户，一位客户也只能买一套房子，问最多能卖出多少套房？

# 分析

在图论里，这是一个典型的[最大流问题](https://baike.baidu.com/item/%E6%9C%80%E5%A4%A7%E6%B5%81%E9%97%AE%E9%A2%98)

将每位客户看作一个点，将每套房子也看作一个点。

新建一个点，作为源点，对每位客户，建立一条源点指向TA的边，容量为1.

对于每对客户和房子，如果房子可能卖给这位客户，则建立一条此客户指向此房子的边，容量为1.

新奸一个点，作为汇点，对每套房子，建立一条TA指向汇点的边，容量为1.

问题就变成了求此图的最大流。

我这里不打算讲什么最大流最小割及相关的定理，也不讲其他的算法，直接进入正题，Dinic算法。

## 残量网络
首先，引入一个残量网络的概念。

在上面的图里，假设找到了一条源点到汇点的路，然后减去相应的流量，那之后如果发现这条路中有一段不是最优的，想要回退怎么办？

办法是，建立正向边的同时，也建立初始容量为0的反向边，减去流量时，正向边减去相应流量，反向边加上相应流量，则之后若有流量通过反向边，则就是回退了。

以上面的情况为例。假设房子j可能卖给客户i，则建立一条i->j的边，容量为1，同时建立一条j->i的边，容量为0，如果将房子j卖给i，则i->j的边减去容量1，j->i的边加上容量1。
之后若做调整，j不卖给i了，则j->i的边减去容量1，i->j的边加上容量1，状态就又变回来了。

加上了反向边的网络，就是残量网络了。

## 增广路
有了残量网络，就容易引入另一个概念了，增广路。

听起来好像是什么新东西，其实很简单，如果在残量网络里能够找到一条从源点到汇点的路径（路径上的每条边容量都大于0），这条路径就叫增广路。

可以想到，定义一条增广路的容量=此增广路上所有路径的容量最小值为m，则此条增广路就可以为最后结果增加m的流量。
作以下操作，给最终流量加上m，给这每条路径减去m容量，对应的反向边加上m，再继续找增广路，如此反复，最后找不到增广路了，最终流量就是最大流。

Dinic算法的基本思想就是不断找增广路，但是它引入了节点层次的概念，帮助加速处理。

## 节点层次
节点层次是这样计算的：
1. 源点的层次为1.
2. 源点能够1步走到（通过一条容量大于0的）的所有点层次为2，能够2步走到的为3，。。。能够n-1步走到的为n.
3. 源点走不到的点，层次为无穷大。

## Dinic算法流程
有了上面的概念，算法流程就有了：
1. 初始化，建立残量网络图。设置图流量flow=0
2. 计算节点层次。
    * 若汇点层次为无穷大，说明没有路可以从源点走到汇点了，退出。
    * 若汇点层次不为无穷大，则说明还有增广路，flow还能继续增加。转3.
3. 不断寻找增广路。找增广路的时候有一条规则：
    * 对于增广路上的每条边i->j，j的层次 = i的层次+1。
    令找增广路的函数为dfs(i, f)，i为当前遍历到的节点，f为当前可通过流量。一开始，调用dfs(源点，无穷大)，递归处理方式如下：
    1. 如果i为汇点，说明已经找到了增广路，返回f. 否则，对于以i为边起点的每条边i->j，容量为c：
        * 如果c > 0, 且j的层次 = i的层次 + 1，继续遍历dfs(j, min(f, c))。
            * 如果dfs(j, min(f, c))返回值r大于0，说明找到了增广路，将边i->j容量减去r，边j->i容量加上r，继续返回r
            * 如果dfs(j, min(f, c))返回值r等于于0，继续处理
        * 否则，继续处理下一条边
    2. 如果上一步所有边都没有返回，返回0.
    如果dfs(源点，无穷大)的值大于0，给flow加上此返回值，继续再找增广路。否则转2。
    这里注意一点，可以记录每个节点当前正在遍历的边（在计算节点层次之后此值重新设置为节点的第一条边），下次继续找增广路时，遍历到某节点时，直接从对应的正在遍历的边继续处理就好。

最后输出flow，就是最大流。

具体代码见[solve.php](./solve.php)