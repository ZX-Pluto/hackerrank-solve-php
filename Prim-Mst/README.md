# Prim's (MST) : Special Subtree
原题见[这里](https://www.hackerrank.com/challenges/primsmstsub/problem)

给定一个无向连通图，要求找它的最小生成树。最小生成树是由原图的全部节点和一部分边构成的，满足以下条件：
* 每个节点到另一个节点只有唯一的一条路径
* 此图在所有满足条件的子图中，全部边的权值加起来是最小的。

文中给了Prim算法的[wiki地址](https://en.wikipedia.org/wiki/Prim%27s_algorithm)

# 分析
Prim算法，我这里简单讲一下好了：

首先做符号约定，假设已经在最小生成树中的点集为S，未在最小生成树中的点集为N。
1. 选择一个起点s，加入到S。
2. 选择这样一条边，它的两端节点分别在S和N中，且它是这样的边中权值最小的。
3. 将2中边加入到最小生成树路径中，将对应的N中的点，加到S中。
4. 如果S已经包含了所有的点，结束，否则，转2.

实际操作过程中，可以用一个堆来维护当前N中到S任一节点距离最短的节点（跟维护边权值最小是等价的），每一步都拿出最短的节点加入到S中，再更新与其相连的点的距离即可。

代码里有相应的注释，这里就不重复具体过程了

具体代码见[solve.php](./solve.php)