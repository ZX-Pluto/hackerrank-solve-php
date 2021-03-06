# Roads and Libraries
原题见[这里](https://www.hackerrank.com/challenges/torque-and-development/problem)

原本有n个城市，每个城市都有一座图书馆, 有m条路，每条路连接两个城市。当前情况下，每个人都能去图书馆，去自己城市那座就行。  
然而，天有不测风云，来了一阵龙卷风，把所有的图书馆和路都给毁了。  
现在要想办法以最少的钱来修复图书馆和路，使得修复之后每个人也都能去图书馆，不论是自己所在城市有，还是能通过路走到其他有图书馆的城市都行。

# 分析
修复一座图书馆所需的钱为clib， 修复一条路所需的钱为croad。策略如下：
* 如果 clib < croad， 那么直接修复所有的图书馆就好。  
* 否则， 就尽量少修图书馆， 多修路， 让路能通到某座图书馆城市的其他城市都通过来就好。  
  
不敢说这策略很显然，更不敢说易证，因为可能你的易证，别人要想一天。  
  
先来证明第一种情况。  
假设现在最省钱的情况，至少需要修复一条路， 在这里我们选择这样一条路，它连接的两个城市为i, j， 且i有图书馆，j没有。  
这样的一条路一定是存在的，如果不存在， 则所有的路连接的两个城市，要么都有图书馆，要么都没有。
* 如果都有，则这条路没有意义，可以移除，跟最优解矛盾。
* 如果都没有，则所有路连接的所有城市都没有图书馆， 跟修复条件矛盾。
  
此时如果移除这条路，同时在城市j建图书馆，则j及能走到j的其他城市依旧能满足可到图书馆，同时花费更少，与最优解矛盾。  
所以对于情况一，修复所有图书馆就好。而且，实际上clib与croad值相同时也是一样，这个可以自己思考下为什么。  

对于情况二，考虑城市之间的连通性，可以把所有彼此可达的城市划分为一个子集， 做法等价于
* 在任何一个两两可达的城市的子集中，选一座城市修图书馆，并修复最少的路，让其他城市可走到此城市即可

可以看到，任何两个子集都是没有交集的，它们能去的图书馆无论如何也不可能相同。因此任意两个子集的修复方式互不影响，每个子集都花费最少，整体就是最少的。  
对于子集的处理， 有算法如下：
1. 任选其中一个城市，修建图书馆, 将其标记为可到达图书馆，其他子集中的城市标记为不可到达。将此城市设为当前遍历城市。
2. 对于当前遍历城市i， 以如下方式递归遍历：
    * 对于每一个与其相连的城市j，如果标记为不可达，则修复i与j之间的道路，将j标记为可达，继续遍历j

假设子集为S，其中城市数量为n。 每个城市只会被遍历一次， 起始城市遍历时建图书馆，其他城市遍历时修复路，那么，以上算法的花费为clib + (n-1)*croad. 下面证明这花费是最小的。
首先，至少需要有一座图书馆。   
原图中未修复的的路是能保证它们相互连通的， 因此递归遍历的方式一定可以遍历到每个城市。  
而对于n个城市， 至少需要n-1条路，才能两两连通。 可以用数学归纳法证明：
1. n=1时，显然成立
2. 假设i个城市时成立，则对于i+1城市，假设编号为1到i+1， 则编号1到i的城市也是两两相通的， 它们需要至少 i - 1条路。
而对于编号为i+1的城市， 它至少需要一条路来与前i个城市中的任何一个相连， 因此至少需要i条路。

而且实际上，没有必要想办法划分子集，从某个城市，能够到达的全部城市，它们就构成了一个子集， 只需要不断的选一个新的可以作为修图书馆的城市，对其遍历即可。

讲了这么多，头都晕了，还是看代码吧[solve.php](./solve.php)