# -*- coding: utf-8 -*-
from math import sqrt
from math import pi
import phylab
from jinja2 import Environment
from handler import texdir

# texdir = "./tex/"
env = Environment()


def Inertia(m, d, T, l, T2, source):
    # 所有测得的数据必须是5倍周期
    I = [-1]  # 实际值
    J = [-1]  # 理论值
    delta = []
    for a in T:
        a.append(sum(a) / len(a) / 5)
    # 计算扭转常数k
    temp = m[0] * pow(d[0], 2) * pow(10, -9) / 8
    I.append(temp)
    J.append(temp)
    k = 4 * pow(pi, 2) * temp / (pow(T[1][-1], 2) - pow(T[0][-1], 2))
    for i in range(2, 5):
        # 圆筒转动惯量
        if i == 1:
            temp1 = (pow(T[2][-1], 2) - pow(T[0][-1], 2)) * k / (4 * pow(pi, 2))
            temp2 = m[1] * (d[1] ** 2 + d[2] ** 2) * pow(10, -9) / 8
        # 球转动惯量
        elif i == 2:
            temp1 = pow(T[3][-1], 2) * k / (4 * pow(pi, 2))
            temp2 = m[2] * pow(d[3], 2) * pow(10, -9) / 10
        # 细杆转动惯量
        else:
            temp1 = pow(T[4][-1], 2) * k / (4 * pow(pi, 2))
            temp2 = m[3] * pow(d[4], 2) * pow(10, -9) / 12
        I.append(temp1)
        J.append(temp2)
        delta.append(abs(J[i] - I[i]) * 100 / J[i])  # 百分之多少

    # 验证平行轴定理
    for a in T2:
        a.append(sum(a) / len(a))
    # 线性回归计算
    x, y, xy, x2, y2 = [], [], [], [], []
    temp = 0
    for i in range(5):
        temp += 5
        x.append(temp ** 2)
        x2.append(x[i] ** 2)
        y.append(pow(T2[i][-1] / 5, 2))
        y2.append(y[i] ** 2)
        xy.append(x[i] * y[i])
    ave_x = sum(x) / len(x)
    ave_y = sum(y) / len(y)
    ave_x2 = sum(x2) / len(x2)
    ave_y2 = sum(y2) / len(y2)
    ave_xy = sum(xy) / len(xy)
    b = (ave_x * ave_y - ave_xy) / (ave_x ** 2 - ave_x2) * pow(10, 4)
    a = ave_y - b * ave_x * pow(10, -4)
    r = abs(ave_xy - ave_x * ave_y) / sqrt((ave_x2 - ave_x ** 2) * (ave_y2 - ave_y ** 2))
    I1 = m[4] * (l[0] ** 2 + l[1] ** 2) * pow(10, -9) / 16 + m[4] * l[2] ** 2 * pow(10, -9) / 12
    I2 = m[5] * (l[0] ** 2 + l[1] ** 2) * pow(10, -9) / 16 + m[5] * l[2] ** 2 * pow(10, -9) / 12
    b1 = (m[4] + m[5]) * 4 * pi ** 2 / k / pow(10, 3)
    a1 = (J[3] + I1 + I2) * 4 * pi ** 2 / k
    res = [r, b]
    len_T = [3, 3, 3, 3, 3]
    return env.from_string(source).render(
        m=m,
        d=d,
        T=T,
        len_T=len_T,
        K=k,
        I=I,
        J=J
    )


def handler(XML):
    file_object = open(texdir + "Handle1010212.tex", "r")
    # 将模板作为字符串存储在template文件中
    source = file_object.read().decode('utf-8', 'ignore')
    file_object.close()
    m = [711.71, 697.76, 1242.40, 131.50, 239.84, 239.57]
    d = [99.95, 99.95, 93.85, 114.60, 610.00]
    T = [[4.21, 4.21, 4.21],
         [6.80, 6.80, 6.80],
         [8.40, 8.39, 8.38],
         [7.23, 7.23, 7.23],
         [11.57, 11.60, 11.59]]

    l = [34.92, 6.02, 33.05]
    T2 = [[13.07, 13.07, 13.07, 13.07, 13.06],
          [16.86, 16.86, 16.88, 16.87, 16.88],
          [21.79, 21.82, 21.83, 21.84, 21.84],
          [27.28, 27.28, 27.29, 27.27, 27.27],
          [32.96, 32.96, 32.96, 32.97, 32.96]]
    res = Inertia(m, d, T, l, T2, source)
    return res


if __name__ == '__main__':
    print handler('')
