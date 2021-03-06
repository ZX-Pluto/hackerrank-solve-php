# Flatland Space Stations
原题见[这里](https://www.hackerrank.com/challenges/flatland-space-stations/problem)

n个城市，其中m个有空间站，对于每个城市到最近的空间站的距离，求其中的最大值。

以题目中给的数据为例，其中0和4是有空间站的。
* [0] 1 2 3 [4]

则每个城市到最近空间站的距离分别是
* 0 1 2 1 0

最大值是2。

# 分析
对于有空间站的城市，当然距离是0。

对于没有空间站的城市，分为三类。
1. 在第1个空间站之前的城市。最近的空间站就是第1个空间站。
2. 在任意两个空间站（中间没有其他空间站）之间的城市。最近的空间站是之前或之后的空间站。
3. 在最后1个空间站之后的城市。最近的空间站是最后那个空间站。

要计算每个城市到最近空间站的距离，就可以这样处理。

然而，实际上，并不需要计算每个城市的最近距离，对于这三类城市，直接找出每一部分距离的最大值。
1. 在第1个空间站，假设序号为s，之前的城市。最大距离就是0号城市到此空间站的距离，也就是s
2. 两个空间站（中间没有其他空间站）i和j之间的这部分城市里，它们之间的最大值就是最中间的一个或两个城市到相应空间站的距离，即(j - i)/2，向下取整。
3. 在最后1个空间站，假设序号为e， 之后的城市，最大距离就是 n - 1号城市到此空间站的距离， 即 n - 1 - e。

依旧举例说明：
* 0 1 [2] 3 4 5 [6] 7 8 9 10 [11] 12

第1个空间站index为2， 在其之前的所有城市的最大距离为是0号城市到此的距离，2。

2和6两个空间站之间， 最大距离是 floor((6 - 2)/2) = 2，对应4号城市

6和11两个空间站之间， 最大距离是 floor((11 - 6)/2) = 2，对应8号和9号城市。

11空间站之后，最大距离是 12 - 11 = 1。

所以依次遍历空间站，求出每一部分的最大值，它们中的最大者就是要求的结果。

具体代码见[solve.php](./solve.php)