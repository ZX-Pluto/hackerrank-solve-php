# Funny String
原题见[这里](https://www.hackerrank.com/challenges/funny-string/problem)

给定一个字符串s,将其反转生成新的字符串s'。

对于这两个字符串，分别计算它们位置0与位置1字符的ascii码之差的绝对值，位置1与位置2的，位置2与位置3的，直到末尾。

如果这两个字符串通过此方式计算出的所有对应位置的值都一样，则输出"Funny"，否则输出"Not Funny"。

例如，字符串s = 'lmnop'，对应的ascii码数组为[108, 109, 110, 111, 112]，将其反转后s' = 'ponml'，对应ascii码数组为[112, 111, 110, 109, 108]。
* 对于s，各相邻位置ascii码之差的绝对值为[|109 - 108|, |110 - 109|, |111 - 110|, |112 - 111|] = [1, 1, 1, 1]
* 对于s',各相邻位置ascii码之差的绝对值为[|111 - 112|, |110 - 111|, |109 - 110|, |108 - 109|] = [1, 1, ,1, 1]

因此是相同的，输出"Funny。"

# 分析

这个题目已经将算法解释得很清楚了，依次计算相邻位置并进行比较即可。

实际处理过程中，考虑到对称性，我是直接处理原始字符串，从两头向中间遍历，如果碰到了有不一样的，输出"Not Funny"，而如果两边遍历的位置会合了，则输出"Funny"。

具体代码见[solve.php](./solve.php)