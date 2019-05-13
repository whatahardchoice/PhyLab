# -*- coding: utf-8 -*-

"""
    欢迎你，贡献者！
    这是目前物理实验平台使用的脚本模板，版本v0.1
    你正在编辑 2160115 实验脚本

    在这个脚本中，除handler函数必须存在外，其他函数都可以以你喜欢的形式编写
    只要满足handler函数的输入和输出要求即可
    以下给出了推荐的一种写法，不完美但够用

    有问题/建议/意见请联系：hardchoice@163.com

    Happy Coding！

                                WhatAHardChoice 团队
                                        2019.4.17

"""

import phylab #这里有一些定义好的工具函数可以使用
from jinja2 import Environment
from handler import texdir
from handler_md import mddir
from handler import scriptdir
import xml.dom.minidom
import math
import sys
import matplotlib.pyplot as plt
import numpy as np

# 以上包必须引入，否则脚本无法运行

# 此处定义jinja2模板引擎的识别符号，默认为||（两个竖线），你也可以定义自己的识别符号，仅需修改
# variable_start_string 和　variable_end_string 即可
env = Environment(line_statement_prefix="#", variable_start_string="||", variable_end_string="||")

# 在此你可以预先写好一些会被绑定至latex文本的全局变量，方便查看
#################
INPUT_A = 0
INPUT_B = 0
RESULT = 0
#################

"""
    handler:脚本入口，为平台指定的对外接口，最好不要改动
        输入：XML对象
        输出：绑定后的文本

    handler是脚本中必须存在的函数，请不要修改file_object.close()前的内容，包括open中的文件名
    此函数最后必须返回jinja2绑定好的数据。

"""
def handler(XML, type):
    if type == 1:
        file_object = open(texdir + "Handle2160115.tex" , "r",encoding='utf-8')
    else:
        file_object = open(mddir + "Handle2160115.md" , "r",encoding='utf-8')
    source = file_object.read()
    file_object.close()
    # 以上勿动！
    ################### 自由发挥分割线 #######################
    # 这里你可以自定义一些数据处理的方式，但最后一定要返回jinja2绑定好的文本！
    data = read_xml(XML)
    return process_data(data[0][0],data[1], source , sys.argv[3])
    

"""
    read_xml: 读取xml文档并将数据解析至list
        输入：XML对象
        输出：一个包含数据的多维list，格式为data['表编号']['行编号']['列编号'] (从0开始)
            例如第一个表第二行第三个数据为data[0][1][2]
        
    你也可以修改自己的read_xml使其支持更复杂的数据读取

"""
def read_xml(root):
    table_list = root.getElementsByTagName("table")
    data = []
    for table in table_list:
        table_tr_list = table.getElementsByTagName("tr")
        trdata = []
        for tr in table_tr_list:
            tr_td_list = tr.getElementsByTagName("td")
            trdata += [[float(x.firstChild.nodeValue) for x in tr_td_list]]
        data += [trdata]
    return data

"""

    process_data: 处理数据
        输入：read_xml得到的数据列表， 模板文本
        输出：绑定后的文本

    此函数的主要作用是处理数据并进行绑定，你可以依照自己的喜好编写，但尽量在此返回数据

    render后括号中以逗号分割的表达式为绑定的对应关系
        模板中变量名 = python脚本中变量（可以是数值、列表、字符串）

    由于表达式右侧可以是字符串，因此可以在此进行一些数据格式化操作，如保留小数等

    注意如果右侧是列表那么左侧在模板中也应当以列表呈现（模板中的for循环使用）

"""
def cat_q(u , t):

    data = []

    temp = t * (1+0.02264 * math.pow(t , 0.5) ) 
    temp = math.pow(temp , 1.5)
    e0 = 1.6021773e-19
    
    temp = 0.9277e-14 / (temp * u )
    #temp = round(temp , 21)
    data.append( temp )

    data.append( round( data[0]/e0 ) )

    temp = data[0]/data[1]
    #temp = round(temp , 21)
    data.append( temp )
	
    temp = (abs(data[2]-e0))/e0
    temp = round( temp * 100 , 2 )
    data.append( temp )
    
    return data

def process_data(u , t  , source , name):
    # 以下例子做了简单的加法
    #INPUT_A = data[0][0][0]
    #INPUT_B = data[0][0][1]
    #RESULT =  INPUT_A + INPUT_B
    
    ave_t = []
    q = []
    n = []
    e = []
    eta = []
    
    for i in range(6):
      ave_t.append("%.2f" % np.mean(t[i]))
      temp = cat_q(u[i] , np.mean(t[i]))
      q.append(temp[0])
      n.append(temp[1])
      e.append(temp[2])
      eta.append(temp[3])
      
    ave_e = np.mean(e)
    
    e0 = 1.6021773e-19
    eta_f = abs(ave_e-e0)/e0
    ua = phylab.Ua(e , ave_e ,6)
    #ua = 0.0
    
    #q = [0,0,0,0,0,0]
    #n = [0,0,0,0,0,0]
    #e = [0,0,0,0,0,0]
    #eta = [0,0,0,0,0,0]
    #eta_f = 0
    #ave_e = 0
    
    font = {
        'size' : 30 ,
    }
    
    pic = name + '_pic1'
    for i in range(6):
    	plt.plot( [0,n[i]] , [0 , q[i]*1e19] ,linewidth = 1.0)
    plt.xlabel('n' , font)
    plt.ylabel('Q' , font)
    plt.savefig(pic , bbox_inches='tight')
    
    return env.from_string(source).render(
        U = u,
      	t0 = t[0],
      	t1 = t[1],
      	t2 = t[2],
      	t3 = t[3],
      	t4 = t[4],
      	t5 = t[5],
      	ave_t0 = ave_t[0] ,
      	ave_t1 = ave_t[1],
      	ave_t2 = ave_t[2],
      	ave_t3 = ave_t[3],
      	ave_t4 = ave_t[4],
      	ave_t5 = ave_t[5],
        q0 = q[0],
      	n0 = n[0],
      	e0 = e[0],
      	eta0 = eta[0],
      	q1 = q[1],
      	n1 = n[1],
      	e1 = e[1],
      	eta1 = eta[1],
      	q2 = q[2],
      	n2 = n[2],
      	e2 = e[2],
      	eta2 = eta[2],
      	q3 = q[3],
      	n3 = n[3],
      	e3 = e[3],
      	eta3 = eta[3],
      	q4 = q[4],
      	n4 = n[4],
      	e4 = e[4],
      	eta4 = eta[4],
      	q5 = q[5],
      	n5 = n[5],
      	e5 = e[5],
      	eta5 = eta[5],
      	eta = eta_f,
      	ave_e = ave_e,
      	U_a = ua,
      	figurename = pic
    )
    
'''
    print(u)
    print(t)
    print(q)
    print(n)
    print(e)
    print(eta)
    print(ave_t)
    print(eta_f)
    print(ua)
    print(ave_e)
    print(source)
    
    return env.from_string(source).render(
        ave_t0 = ave_t[0]
        )
    
'''

		#INPUT_A = "%.2f" % INPUT_A, #保留两位小数
        #INPUT_B = "%.2f" % INPUT_B,
        #RESULT = "%.2f" % RESULT




if __name__ == '__main__':
    scriptdir = 'C:/Users/98239/Desktop/temp/script/'
    texdir = scriptdir + 'tex/'
    root = ''
    dom=xml.dom.minidom.parse(scriptdir + 'test/2160115test/2160115.xml')
    root = dom.documentElement
    print(handler(root))

