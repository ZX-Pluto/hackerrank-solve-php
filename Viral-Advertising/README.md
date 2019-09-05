# Viral Advertising
原题见[这里](https://www.hackerrank.com/challenges/strange-advertising/problem)

有一件新产品发布了，那得发广告啊。  
第一天，把广告通过社交媒体（邮件啊，微信啊，这里假设是微信吧）发给5个人。 这些人里有一半（除不尽则向下取整），也就是2个人，觉得这东西有意思。
第二天，第一天觉得有意思的那两个人，成为了自来水，每人又把广告转发给了3个人。于是当天有6个人收到了广告， 同样的， 这6个人里有一半，3个人，觉得有意思。  
之后每一天继续这样处理，当天收到广告的所有人里面，会有一半人觉得有意思，并在第二天都把广告转发给3个人，并且只发给以前没有收到过的人。  
给定一个天数n， 问第n天时，一共有多少人会对广告有兴趣。

# 分析
按照题意， 假设第i天对广告有兴趣的人为d(i)， 则有公式如下：
* d(1) = 2;
* d(i+1) = floor(d(i)*3/2);  i = 1,2,3,....
其中floor表示向下取整。 而最后要输出的第n天有兴趣的人总数就是 d(1) + d(2) + ... + d(n)。 

依次遍历累加就好，具体代码见[solve.php](./solve.php)