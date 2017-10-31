# -*- coding: utf-8 -*-
# 多光束及法布里伯罗干涉
'''
input:
    
output:

'''
import xml.dom.minidom
from math import sqrt
import numpy as np
from jinja2 import Environment
from handler import texdir
from handler import scriptdir
from phylab import *
# 原始数据

# 数据处理得到的数据
env = Environment(line_statement_prefix="#", variable_start_string="%%", variable_end_string="%%")

def readXml2160115(root, source):
  """
    table_list = root.getElementsByTagName("table")
    table = table_list[0]
    data = []
    # lambda的值
    for tr in table_list[0].getElementsByTagName("tr"):
        tr_td_list = tr.getElementsByTagName("td")
        if tr_td_list[0].firstChild:
            Lambda = float(tr_td_list[0].firstChild.nodeValue)
            Lambda = Lambda * 0.000000001
    # di的值
    y = [] #y是di
    for tr in table_list[1].getElementsByTagName("tr"):
        tr_td_list = tr.getElementsByTagName("td")
        if tr_td_list[0].firstChild:
            temp = float(tr_td_list[0].firstChild.nodeValue)
            y.append(temp)
    #证明的lambda 和 f 的值
    temp = []
    for tr in table_list[2].getElementsByTagName("tr"):
        tr_td_list = tr.getElementsByTagName("td")
        if tr_td_list[0].firstChild:
            temp.append(float(tr_td_list[0].firstChild.nodeValue))
        if tr_td_list[1].firstChild:
            temp.append(float(tr_td_list[1].firstChild.nodeValue))
    lambdaa = temp[0] * 0.000000001
    f = temp[1] * 0.001
    #li左 和 li右
    d_l = []
    d_r = []
    for tr in table_list[3].getElementsByTagName("tr"):
        tr_td_list = tr.getElementsByTagName("td")
        if tr_td_list[0].firstChild:
            d_l.append(float(tr_td_list[0].firstChild.nodeValue))
        if tr_td_list[1].firstChild:
            d_r.append(float(tr_td_list[1].firstChild.nodeValue))
    [x, x2, y2, xy, delta_lambda, u_delta_lambda, delta, r, b, u_b] = cal_delta_lambda(y, Lambda)
    [i, D, D2, i2, D4, D2i, bb, rr, d] = prove(d_l, d_r, lambdaa, f)
    RoundOne(y,3)
    RoundOne(y2,4)
    RoundOne(xy,4)
    RoundOne(D2,3)
    RoundOne(D4,3)
    RoundOne(d_l,3)
    RoundOne(d_r,3)
    D2_aver = np.average(map(eval,D2))
    D4_aver = np.average(map(eval,D4))
    y_aver = np.average(map(eval,y))
    y2_aver = np.average(map(eval,y2))
    xy_aver = np.average(map(eval,xy))
    delta_lambda_u_delta_lambda = BitAdapt(delta_lambda , u_delta_lambda)
    delta_lambda_normal = ""
    for z in delta_lambda_u_delta_lambda :
        if z != '(' and z != '\\':
            delta_lambda_normal += z
        if z == '\\':
            break
    return env.from_string(source).render(
            x = x,#第一个表格的xi
            x2 = x2,#第一个表格的xi的平方
            y = y,#第一个表格的yi
            y2 = y2,#第一个表格的yi的平方
            xy = xy,#第一个表格的xiyi
            Lambda = Lambda,
            lambdaa = lambdaa * 1000000000,
            x_aver = np.average(x),#上面几项的平均值
            y_aver = round(y_aver, 3),
            x2_aver = np.average(x2),
            y2_aver = round(y2_aver, 4),
            xy_aver = round(xy_aver, 4),
            i = i,#第二个表格的次数xi
            D2 = D2,#第二个表格的yi
            i2 = i2,#第二个表格的xi的平方
            D4 = D4,#第二个表格的yi的平方
            D2i = D2i,#第二个表格的xiyi
            i_aver = np.average(i),#上面几项的平均值
            D2_aver = round(D2_aver, 3),
            i2_aver = np.average(i2),
            D4_aver = round(D4_aver, 3),
            D2i_aver = np.average(D2i),
            delta_lambda = round(delta_lambda,5),#单位nm
            u_delta_lambda = round(u_delta_lambda,7),#单位nm
            delta = round(100 * delta, 2),#相对误差
            r = round(r,5),#第一问的相关系数
            b = round(b, 7) * 10000,#第一问的b，单位m
            u_b = round(u_b*10000000,4),
            bb = round(bb * 100000, 4),#证明的b，单位m
            rr = round(rr, 6),#证明的相关系数
            d = round(d, 5),#证明最后的差值（常数），单位m
            d_l = d_l,#原始数据表格
            d_r = d_r,
            D = D,
            f = f * 1000,
            delta_lambda_u_delta_lambda = delta_lambda_u_delta_lambda,
            delta_lambda_normal = delta_lambda_normal
            #D方就是上面的D2
            
            )
    """

def prove(d_l, d_r, lambdaa = 632.8 * 0.000000001, f = 150 * 0.001):
    D = range(0, len(d_l))
    D2 = range(0, len(d_l))
    D4 = range(0, len(d_l))
    D2i = range(0, len(d_l))
    for i in range(len(d_l)):
        D[i] = d_l[i] - d_r[i]
        D2[i] = D[i] * D[i]
        D4[i] = D2[i] * D2[i]
        D2i[i] = D2[i] * (i + 1)
    i = range(1, len(D) + 1)
    i2 = range(1, len(D) + 1)
    for j in range(len(i)):
        i2[j] = i2[j] * i2[j]
    bb = (np.average(i) * np.average(D2) - np.average(D2i)) / (np.average(i)**2 - np.average(i2)) / 1000000 #mm
    rr = (np.average(i) * np.average(D2) - np.average(D2i)) / sqrt((np.average(i2) - np.average(i)**2) * (np.average(D4) - np.average(D2)**2))
    d = 4 * lambdaa * f**2 / abs(bb)
    return i, D, D2, i2, D4, D2i, bb, rr, d

def cal_delta_lambda(y, Lambda):
    x = range(1, len(y) + 1)
    x2 = range(1, len(y) + 1)
    y2 = range(1, len(y) + 1)
    xy = range(1, len(y) + 1)
    for i in range(len(x)):
        x2[i] = x[i]**2
        y2[i] = y[i]**2
        xy[i] = x[i]*y[i]
    b = (np.average(xy) - np.average(x)*np.average(y)) / (np.average(x2) - np.average(x)**2) * 0.001
    delta_lambda = Lambda**2 / (2 * b)
    r = (np.average(xy) - np.average(x)*np.average(y)) / sqrt((np.average(x)**2 - np.average(x2)) * (np.average(y)**2 - np.average(y2)))
    u_b = b * sqrt((1 / (r**2) - 1) / (len(x) - 2))
    u_delta_lambda = delta_lambda * u_b / b
############
##最终结果
    delta_lambda = delta_lambda * 1000000000
    u_delta_lambda = u_delta_lambda * 1000000000
    #print delta_lambda, u_delta_lambda
#    k = 0
#    temp = 1.0
#    while temp > u_delta_lambda:
#        k+=1
#        temp /= 10
#    u_delta_lambda_int = round(u_delta_lambda * pow(10, k))
#    delta_lambda_int = round(delta_lambda * pow(10, k))
#    u_delta_lambda = float(u_delta_lambda_int) / pow(10, k)
#    delta_lambda = float(delta_lambda_int) / pow(10, k)
###################
    delta = (0.6 - delta_lambda) / 0.6
    return x, x2, y2, xy, delta_lambda, u_delta_lambda, delta, r, b, u_b

def handler(XML):
    file_object = open(texdir + "Handle2160115.tex","r")
    source = file_object.read().decode('utf-8', 'ignore')
    file_object.close()
    return readXml2160115(XML, source)
      
if __name__ == '__main__':
    scriptdir = 'D:/Apache24/htdocs/PhyLabs/Phylab/storage/app/script/'
    texdir = scriptdir + 'tex/'
    dom = xml.dom.minidom.parse(scriptdir + 'test/2160115test/2160115.xml')
    root = dom.documentElement
    print handler(root)