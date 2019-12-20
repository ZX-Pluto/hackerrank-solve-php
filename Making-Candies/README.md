# Making Candies
原题见[这里](https://www.hackerrank.com/challenges/making-candies/problem)

卡尔在玩一个生产蜡烛的游戏。

游戏的胜利条件是：某一回合结束后，手里有n根蜡烛。

一开始，会给你m台机器和w个工人。每回合，能够生产 (当前工人数 * 当前机器数) 根蜡烛。
每回合结束后，你可以拿蜡烛继续雇佣工人或买机器，雇佣1个工人或是买1台机器，都需要花费k根蜡烛。

给定m,w,n和k，问最快能几回合胜利？

# 分析

首先，将处理过程看作一个如下遍历过程。
* 第1回合继续买装备（雇人或买机器）最快几回合胜利 vs 第1回合及之后，都不买装备，最快几回合胜利。
* 前t个回合都买装备，此回合也买装备，最快几回合胜利 vs 前t个回合买了装备，此回合及之后，都不买装备，最快几回合胜利。

注意，这里的买指的是“想买”而不是“能买”，如果是由于蜡烛不够而买不了，也可以算做想买。

那么问题来了，为什么遍历是前若干个回合都买，而后若干个不买呢，如果买跟不买交替进行，不可以么？

假设第i回合不买，第i+1回合买，则如果第i回合买，第i+1回合也买，第i+2回合能够收获更多蜡烛。

设第i回合结束后工人为wi, 机器为mi，蜡烛数为ci。

* 情况一：此回合不买， 第i+1回合结束后，蜡烛数为 ci + mi * wi, 工人数为wi, 机器数为mi
* 情况二：买。假设买a机器，b工人（a跟b至少有一个大于0），第i+1回合结束后，蜡烛数为 ci - a * k - b * k + (mi + a) * (wi + b), 工人数为wi + b，机器数为mi + a

情况二蜡烛数 - 情况一蜡烛数 = a * wi + b * mi + a * b - a * k - b * k。 如果此值 > 0，则显然买更划算。

此值 < 0， 则情况一多出来的这部分，必须要至少能买b个工人和a个机器，第i+2回合生产的蜡烛数才能赶上情况二，才更划算，即必须有
* -(a * wi + b * mi + a * b - a * k - b * k) > a * k + b * k 即 a * k + b * k - (a * wi + b * mi + a * b) > a * k + b * k， 即 - (a * wi + b * mi + a * b) > 0
这个，显然不可能能成立。

这样，除非下一回合蜡烛数就攒够了，否则不会有此回合不买，下一回合买的情况。而下一回合蜡烛数已经够了的话，也就不需要买了，因此不存在前面回合不买，后面回合买的情况。

这个，其实，玩游戏的时候操作会比较直观，比如玩星际，农民采矿得钱，而继续造农民要花钱，那么，现在能造的情况，造不造emmmmmmmm

那么，买的情况下，买多少合适呢？答案是，全买，能买多少买多少。因为，能钱全部拿来买相比，少买t个的情况，就相当于此回合买t个 对比 此回合不买的情况。

全买的话，具体怎么买呢？

首先，能买的数量是一定的，买完之后， 工人数 + 机器数 = wi + mi + 能买的数量， 而肯定是希望下回合能产出更多的蜡烛。

是不是有点熟悉？ 仿佛回到了被高考支配的年代， a + b = 常数， 求 a * b的最大值。 当然是，让a和b越接近越好了。

这样遍历的过程就有了，继续买装备时，尽量让工人数和机器数接近就好。

具体代码见[solve.php](./solve.php)