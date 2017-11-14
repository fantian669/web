# 转载于  http://www.kidscoderepo.com/python.html
#"Recursive Star"
# in Python you have to pay attention to the indentions as they are regrouping blocks of code!

import turtle

def star(turtle, n,r):
# draw a star with n branches of length d
    for k in range(0,n):
        turtle.pendown()
        turtle.forward(r)
        turtle.penup()
        turtle.backward(r)
        turtle.left(360/n)

def recursive_star(turtle, n, r, depth, f):
# At each point of a star, draw another (smaller) star, and repeat this depth times
    if depth == 0:
        star(turtle, n, f*4)
    else:
        for k in range(0,n):
            turtle.pendown()
            turtle.forward(r)
            recursive_star(turtle, n, f*r, depth - 1,f)
            turtle.penup()
            turtle.backward(r)
            turtle.left(360/n)

# This is the actual code of the main loop     
fred = turtle.Turtle()
fred.speed("fastest")
recursive_star(fred, 5 , 150, 4, 0.4)


# "A black and red triangle"
# turtle.color("red", "black")
# turtle.begin_fill()
# for _ in range(3):
#     turtle.forward(100)
#     turtle.left(120)
# turtle.end_fill()
 
# # "A spiral out of squares"
# import turtle
# size=1
# while (True):
#     turtle.forward(size)
#     turtle.right(91)
#     size = size + 1