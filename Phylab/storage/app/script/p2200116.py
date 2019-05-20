# -*- coding: utf-8 -*-

"""
    欢迎你，贡献者！
    这是目前物理实验平台使用的脚本模板，版本v0.1
    你正在编辑 2200116 实验脚本

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
from handler import scriptdir
from handler_md import mddir
import xml.dom.minidom
import pandas as pd
from sklearn import  linear_model
import matplotlib.pyplot as plt
import math
import sys
import numpy as np

# 以上包必须引入，否则脚本无法运行

# 此处定义jinja2模板引擎的识别符号，默认为||（两个竖线），你也可以定义自己的识别符号，仅需修改
# variable_start_string 和　variable_end_string 即可
env = Environment(line_statement_prefix="#", variable_start_string="||", variable_end_string="||")
mdjudge = 1

# 在此你可以预先写好一些会被绑定至latex文本的全局变量，方便查看
#################
#INPUT_A = 0
#INPUT_B = 0
#RESULT = 0
#################

"""
    handler:脚本入口，为平台指定的对外接口，最好不要改动
        输入：XML对象
        输出：绑定后的文本

    handler是脚本中必须存在的函数，请不要修改file_object.close()前的内容，包括open中的文件名
    此函数最后必须返回jinja2绑定好的数据。

"""
def handler(XML , type):
    if type == 1:
        file_object = open(texdir + "Handle2200116.tex" , "r", encoding='utf-8')
    else:
        global env
        env = Environment(line_statement_prefix="@", variable_start_string="%%", variable_end_string="%%")
        file_object = open(mddir + "Handle2200116.md" , "r", encoding='utf-8')
    global mdjudge
    mdjudge = type
    source = file_object.read()
    file_object.close()
    # 以上勿动！
    ################### 自由发挥分割线 #######################
    # 这里你可以自定义一些数据处理的方式，但最后一定要返回jinja2绑定好的文本！
    data = read_xml(XML)
    #print(data)
    #print(source)
    #print(sys.argv[3])
    return process_data(data[0][0][0] , data[0][1] , data[0][2] , data[1][0] , data[1][1] , data[2][0] , data[2][1] , data[3][0][0] , data[3][0][1] , data[3][0][2] , source , sys.argv[3])
    

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
def cat_rad1(N ,dta_s , L , n):
    return (N * dta_s)/(L * n)

def cat_Vs(k):
    #lamda , n 已知
    #lamda为真空中波长
    lamda = 650
    n = 2.386
    return (lamda * 1000000) / (k * n * 1000000000)

def process_data(D , fs1 , div1 , fs2 , div2 , P , I , div3 , I0 , I1 , source , name):
    # 以下例子做了简单的加法
    #INPUT_A = data[0][0][0]
    #INPUT_B = data[0][0][1]
    #RESULT =  INPUT_A + INPUT_B

    #已知量
    n = 2.386
    Vs0 = 3632  #m/s  理论值
    I1_v_div = 0.5
    I0_v_div = 1
    P0 = 86
    theta0 = 0.00653 #理论值
    dta_s = 3.622

    font = {
        'size' : 30 ,
    }

    #D(修正后) , dta_s , fs1 , div1  -> rad1 , Vs , relative_err1 , k1 , pic1
    #fs2 , div2  ->  div2_max , fs2_max , pic2
    #P , I ,I0 -> eta1 , pic3
    #I0 , I1 , div3 ->  theta1 , eta2 ， relative_err2

    
    rad1 = []
    #rad1,科学计数法
    for i in range( len(div1) ):
        rad1.append( (div1[i]*dta_s)/(D*n*1000) )

    
    df=pd.DataFrame({'weight':fs1,'height':rad1})
    x=pd.DataFrame(df['weight'])
    y=df['height']
    clf = linear_model.LinearRegression()
    clf.fit(x , y)
    #k1，科学计数法
    k1 = float(clf.coef_)
    y_pred =clf.predict(x)

    #pic1
    fig1 = plt.figure(figsize=(15,9))
    pic1 = name+'_pic1'
    plt.scatter(x, y,  color='red')
    plt.plot(x,y_pred, color='blue', linewidth=1.5)
    plt.xlabel('f_s' , font)
    plt.ylabel('phi' , font)
    fig1.savefig(pic1+'.png',bbox_inches='tight')

    #Vs整数
    Vs = cat_Vs(k1)
    #relative_err1 百分形式，.2f
    relative_err1 = (abs(Vs-Vs0)*100) / Vs0

    #div2_max , fs2_max
    div2_max = max(div2)
    fs2_max = fs2[div2.index(div2_max)]

    #pic2
    fig2 = plt.figure(figsize=(15,9))
    pic2 = name+'_pic2'
    plt.scatter(fs2 , div2,  color='red')
    plt.plot(fs2 , div2, color='blue', linewidth=1.5)
    plt.xlabel('f_s' , font)
    plt.ylabel('I' , font)
    fig2.savefig(pic2+'.png',bbox_inches='tight')

    #eta = I1/I0
    eta = (I0 * I1_v_div) / (I0 * I0_v_div)

    #pic3
    fig3 = plt.figure(figsize=(15,9))
    pic3 = name+'_pic3'
    plt.scatter(P, I,  color='red')
    plt.plot(P,I, color='blue', linewidth=1.5)
    plt.xlabel('P' , font)
    plt.ylabel('I' , font)
    fig3.savefig(pic3+'.png',bbox_inches='tight')
    
    #theta1 , eta2 , relative_err2
    theta1 = (div3 * dta_s) / (D * n * 1000)
    eta2 = (I1/I0)*100
    relative_err2 = (abs(theta1 - theta0)*100) / theta0

    if mdjudge == 2:
        strs = name.split('/');
        src = '/' + strs[5] + '/' + strs[6]
        pic1 = src + '_pic1'
        pic2 = src + '_pic2'
        pic3 = src + '_pic3'

    return env.from_string(source).render(
        #INPUT_A = "%.2f" % INPUT_A, #保留两位小数
        #INPUT_B = "%.2f" % INPUT_B,
        #RESULT = "%.2f" % RESULT
        D = D ,
        fs1 = fs1 ,
        div1 = div1 ,
        rad1 = list(map(phylab.ToScience , rad1)) ,
        Vs = np.round(Vs , 0) ,
        k1 = phylab.ToScience(k1) ,
        relative_err1 = np.round(relative_err1 , 2) ,
        pic1 = pic1 ,
        fs2 = fs2 ,
        div2 = div2 , 
        div2_max = div2_max ,
        fs2_max = fs2_max ,
        pic2 = pic2 ,
        P = P ,
        I = I ,
        eta = np.round(eta , 2) ,
        pic3 = pic3 ,
        div3 = div3 ,
        I0 = I0 ,
        I1 = I1 , 
        theta1 = phylab.ToScience(theta1) ,
        eta2 = np.round(eta2 , 2) ,
        relative_err2 = np.round(relative_err2 , 2) ,
        )




if __name__ == '__main__':
    scriptdir = 'C:/Users/98239/Desktop/temp/script/'
    texdir = scriptdir + 'tex/'
    root = ''
    dom=xml.dom.minidom.parse(scriptdir + 'test/2200116test/2200116.xml')
    root = dom.documentElement
    print(handler(root))
    """
        此处你可以写一些本地运行的测试，将代码拷贝到本地后执行python脚本即可运行此处代码，服务端不会执行这里的代码
    """

