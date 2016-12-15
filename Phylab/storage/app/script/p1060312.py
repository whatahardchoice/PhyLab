# -*- coding:utf-8 -*-
# 实验三 共轭法测量透镜焦距
"""
mm
input:
    s_pos
    p_pos
    big_1_1
    big_1_2
    big_2_1
    big_2_2
    small_1_1
    small_1_2
    small_2_1
    small_2_2
output:

"""
import xml.dom.minidom
from jinja2 import Environment
from handler import texdir
from phylab import *

env = Environment(line_statement_prefix="#", variable_start_string="%%", variable_end_string="%%")


s_pos = 0
p_pos = 0
big_1_1 = []
big_1_2 = []
big_2_1 = []
big_2_2 = []
small_1_1 = []
small_1_2 = []
small_2_1 = []
small_2_2 = []

b = 0
a = []
average_a = 0
ua_a = 0
ub_a = 0
u_a = 0
ua_b = 0
ub_b = 0
u_b = 0
f = 0
u_f = 0

S_POS = ""
P_POS = ""
BIG11 = ""
BIG12 = ""
BIG21 = ""
BIG22 = ""
SMALL11 = ""
SMALL12 = ""
SMALL21 = ""
SMALL22 = ""
B = ""
A = ""
AVERAGE_A = ""
UA_A = ""
U_A = ""
RESULT = []


def handler(sublab_root):
    xmlReader(sublab_root)
    niconiconi()
    regulation()
    # file_object = open(texdir + "/Handle1060312.tex", "r")
    # latex = file_object.read().decode('utf-8', 'ignore')
    # return lexFiller(latex)


def xmlReader(sublab_root):
    global p_pos, s_pos, big_1_1, big_1_2, big_2_1, big_2_2, small_1_1, small_2_1, small_1_2, small_2_2
    table_list = sublab_root.getElementsByTagName("table")
    table1 = table_list[0]
    table2 = table_list[1]
    row1 = table1.getElementByTagName("tr")
    row1_elements = row1.getElementsByTagName("td")
    s_pos = float(row1_elements[0].firstChild.nodeValue)
    p_pos = float(row1_elements[1].firstChild.nodeValue)
    row_list = table2.getElementsByTagName("tr")
    row1 = row_list[0]
    row1_tds = row1.getElementsByTagName("td")
    for td in row1_tds:
        big_1_1.append(float(td.firstChild.nodeValue))
    row2 = row_list[1]
    row2_tds = row2.getElementsByTagName("td")
    for td in row2_tds:
        big_1_2.append(float(td.firstChild.nodeValue))
    row3 = row_list[2]
    row3_tds = row3.getElementsByTagName("td")
    for td in row3_tds:
        small_1_1.append(float(td.firstChild.nodeValue))
    row4 = row_list[3]
    row4_tds = row4.getElementsByTagName("td")
    for td in row4_tds:
        small_1_2.append(float(td.firstChild.nodeValue))
    row5 = row_list[4]
    row5_tds = row5.getElementsByTagName("td")
    for td in row5_tds:
        big_2_1.append(float(td.firstChild.nodeValue))
    row6 = row_list[5]
    row6_tds = row6.getElementsByTagName("td")
    for td in row6_tds:
        big_2_2.append(float(td.firstChild.nodeValue))
    row7 = row_list[6]
    row7_tds = row7.getElementsByTagName("td")
    for td in row7_tds:
        small_2_1.append(float(td.firstChild.nodeValue))
    row8 = row_list[7]
    row8_tds = row8.getElementsByTagName("td")
    for td in row8_tds:
        small_2_2.append(float(td.firstChild.nodeValue))


def niconiconi():
    global p_pos, s_pos, big_1_1, big_1_2, big_2_1, big_2_2, small_1_1, small_2_1, small_1_2, small_2_2, \
        b, a, average_a, f, ua_a, ua_b, ub_a, ub_b, u_f, u_a, u_b
    size = len(big_1_1)
    sum_a = 0
    for i in range(size):
        a.append(abs(small_1_1 + small_1_2 + small_2_1 + small_2_2 - big_1_1 - big_1_2 - big_2_1 - big_2_2) / 4)
        sum_a += a[i]
    if size > 0:
        average_a = sum_a / size
    else:
        print "no data!"
    b = abs(p_pos - s_pos)
    f = (b*b-a*a)/(4*b)
    ua_a = Ua(a, average_a, size)
    ub_a = 0.5774
    u_a = sqrt(ua_a*ua_a+ub_a*ub_a)
    ua_b = 0
    ub_b = 0.5774
    u_b = sqrt(ua_b*ua_b+ub_b*ub_b)
    u_f = sqrt(pow(((b*b+a*a)/(4*b*b)*u_a), 2)+pow((a/b/2*u_b), 2))


def regulation():
    global S_POS, P_POS, BIG11, BIG12, BIG21, BIG22, SMALL11, SMALL12, SMALL21, SMALL22, B, AVERAGE_A, UA_A, U_A, \
        p_pos, s_pos, big_1_1, big_1_2, big_2_1, big_2_2, small_1_1, small_2_1, small_1_2, small_2_2, \
        b, a, average_a, f, ua_a, ua_b, ub_a, ub_b, u_f, u_a, u_b
    P_POS = ToScience(p_pos)
    S_POS = ToScience(s_pos)
    for d in big_1_1:
        BIG11.append(ToScience(d))
    for d in big_1_2:
        BIG12.append(ToScience(d))
    for d in big_2_1:
        BIG21.append(ToScience(d))
    for d in big_2_2:
        BIG22.append(ToScience(d))
    for d in small_1_1:
        SMALL11.append(ToScience(d))
    for d in small_1_2:
        SMALL12.append(ToScience(d))
    for d in small_2_1:
        SMALL21.append(ToScience(d))
    for d in small_2_2:
        SMALL22.append(ToScience(d))
    for d in a:
        A.append(ToScience(d))
    B = ToScience(b)
    AVERAGE_A = ToScience(average_a)
    UA_A = ToScience(ua_a)
    U_A = ToScience(u_a)
    bitAdapt(f, u_f, 2, -3)


def lexFiller(source):
    complete_file = env.from_string(source).render(
        S_POS=S_POS,
        P_POS=P_POS,
        BIG11=BIG11,
        BIG12=BIG12,
        BIG21=BIG21,
        BIG22=BIG22,
        SMALL11=SMALL12,
        SMALL21=SMALL22,
        SMALL12=SMALL12,
        SMALL22=SMALL22,
        UA_A=UA_A,
        U_A=U_A,
        F=RESULT[0],
        UF=RESULT[1],
    )
    return complete_file
