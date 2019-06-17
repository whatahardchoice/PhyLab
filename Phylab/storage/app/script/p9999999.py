# -*- coding: utf-8 -*-

"""
    欢迎你，贡献者！
    这是目前物理实验平台使用的脚本模板，版本v0.1
    你正在编辑 9999999 实验脚本

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

# 以上包必须引入，否则脚本无法运行

# 此处定义jinja2模板引擎的识别符号，默认为||（两个竖线），你也可以定义自己的识别符号，仅需修改
# variable_start_string 和　variable_end_string 即可
env = Environment(line_statement_prefix="@", variable_start_string="||", variable_end_string="||")

# mdjudge = 1
# 此变量在生成markdown模板需要插入图片时发挥作用


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
        file_object = open(texdir + "Handle9999999.tex" , "r",encoding='utf-8')
    else:
        global env
        env = Environment(line_statement_prefix="@", variable_start_string="%%", variable_end_string="%%")
        # 由于markdown中‘||’会与表格中的‘|’产生冲突，最好用‘%%’代替
        file_object = open(mddir + "Handle9999999.md" , "r",encoding='utf-8')

    # 如需插入图片(由于markdown中图片读取方式不同)
    # global mdjudge
    # mdjudge = type

    source = file_object.read()
    file_object.close()
    # 以上勿动！
    ################### 自由发挥分割线 #######################
    # 这里你可以自定义一些数据处理的方式，但最后一定要返回jinja2绑定好的文本！
    data = read_xml(XML)
    return process_data(data, source)
    

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
def process_data(data, source):
    # 以下例子做了简单的加法
    INPUT_A = data[0][0][0]
    INPUT_B = data[0][0][1]
    RESULT =  INPUT_A + INPUT_B

    # 需要插入图片时
    # if mdjudge == 2:
    #     strs = name.split('/');
    #     pic = '/' + strs[5] + '/' + strs[6]

    return env.from_string(source).render(
        INPUT_A = "%.2f" % INPUT_A, #保留两位小数
        INPUT_B = "%.2f" % INPUT_B,
        RESULT = "%.2f" % RESULT
        # figurename = pic
        )





if __name__ == '__main__':
    """
        此处你可以写一些本地运行的测试，将代码拷贝到本地后执行python脚本即可运行此处代码，服务端不会执行这里的代码
    """

