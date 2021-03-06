# Lena Sort
原题见[这里](https://www.hackerrank.com/challenges/lena-sort/problem)

给定Lena Sort的定义。

其实就是类似快速排序，选当前数组第一个元素作为基准pivot，拿其他元素跟它比，比它小的放入一个less数组，比它大的放入一个more数组，然后再递归的对less和more进行排序。

最后将输出排序好的less，pivot以及排序好的more拼接在一起，返回。

现在，定义每当有pivot的其他元素进行比较，就叫一次comparison。

现在给定len和c，要求构建长度为len的数组，数组中的每项元素都不相同，而完成此数组排序，正好需要c次comparison。若无法构建，则输出-1.

# 分析
首先说一句，这道题

我先讲讲我一开始想到的做法，然后再讲讲官方给的做法。

## 我的做法
做的时候，我非常直觉的觉得，对于每一轮分成less和more的处理，如果每次less和more数量都接近，则最终比较次数是最小的，而如果每次less和more有一个为空的话，则最终比较次数是最大的。

最坏的情况肯定是样，但是最好的情况我不知道应该怎样证明。

还有一点，非常重要的一点，我也不知道怎么证明，后来看了官方的讲解才明白。那就是在最坏情况和最好情况之间的所有次数，都是可以构建出来的。

于是我得出了这样的算法：
1. 计算对于任意长度的数组，最好需要多少次比较，最坏需要多少次。假设对应数组分别为minArr和maxArr
2. 如果对于给定的len，对应的c小于最好情况的比较数，或大于最坏情况的比较数，则返回-1.否则转3.
3. 定义process(len, c)操作如下：
    1.如果len <= 1，不处理。 
    2.找出两个数a和b，使得满足以下条件：
        * a + b = n - 1
        * minArr[a] + minArr[b] <= c - (n - 1)
        * maxArr[a] + maxArr[b] >= c - (n - 1)
        也就是找到若对当前数组调用Lena Sort，能够继续满足条件的拆分方式。
    3. 然后再确定c和d使得:
        * c + d = c - (n - 1)
        * minArr[a] <= c <= maxArr[a]
        * minArr[b] <= d <= maxArr[b]
    4. 继续调用process(a, c)和process(b, d)递归遍历

当然我这个过程中间有一点没有写，对于给定的len，也需要给出数组元素最小值minValue，对应的最大值就是minValue + len - 1，这样在上面递归的过程中就能得出原始数组对应位置的值了。

第2步和第3步的找数，都是用二分查找。

具体代码见[solve-my.php](./solve-my.php)

## 官方做法
首先，对于整个比较过程，可以看作一棵二叉树，每个节点的值就是当前要处理数组的pivot，而左右子树分别就是排序好的less和more。

而每个节点的高度，就是它的值在之前与之前所在数组的pivot比较的总次数。可以这样理解，对于任意一个元素，它跟当前处理数组的pivot比较一次，就一定会被分到less或more，也就是往下走一层，高度+1。

所以，最终，比较总次数，就是所有节点的高度之和。

接下来，就可以证明，最坏情况和最好情况之间的所有次数，都是可以构建出来的了。

首先，构建最坏情况的二叉树，所有节点只有左子树。

然后，每一步做这样的操作，选择当前层数最高的节点，将其上移一层。当然，这样的移动，原始数组的元素顺序是会发生变化的，但是没有关系，节点总数不变，树对应的数组就一定存在。

而每一步这样的操作，所有节点高度之和就减1，持续这样的操作，直到无法再移动为止，这之间所有的次数，就都可以达到了。

与此同时算法也就出来，就是先构建最坏的二叉树，然后不断移动节点，更新高度之和，直至达到要求的数目为止。

而且，在移动过程中也不是真的一步移一层，而是可以一步移动到当前能够移动到的最好位置。因为是二叉树，每一层的节点最大数量是固定的，可以直接移动到当前有空闲的最低层去，或者是移动到满足要求的层数去。

具体代码见[solve.php](./solve.php)