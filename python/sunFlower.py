'''转载：http://www.cnblogs.com/nowgood/p/turtle.html
使用turtle 绘画太阳花
'''
import turtle as t
import time
t.color("red", "yellow")
t.speed(10)
t.begin_fill()
for _ in range(50):
    t.forward(200)
    t.left(170)
t.end_fill()
time.sleep(1)

