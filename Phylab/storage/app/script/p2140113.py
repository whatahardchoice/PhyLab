# -*- coding: utf-8 -*-

import phylab
import matplotlib.pyplot as plt
from math import sqrt
import sys
from jinja2 import Environment
from handler import texdir
from handler import scriptdir
import xml.dom.minidom

env = Environment(line_statement_prefix="#", variable_start_string="||", variable_end_string="||")

def readXml2140113(root):
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


def FuelCell(I1,U1,t,Vc,T,U2,I2,U3,I3,Isc,Uoc,source,name):

    #实验1
    #I1[] 输入电流，单位：A，长度3
    #U1[] 输入电压，单位：V，长度3
    #t[] 时间，单位：s，长度3
    #Vc[] H2产生量测量值，单位：ml，长度3
    #T 实验室温度，单位：℃

    #Q1[] 电量，单位：C,长度3
    #Vl[] H2产生量理论值，单位：ml，长度3
    #A[] 相对误差

    F = 96500 #H2计算公式中的量

    #电量计算
    Q1 = []
    for i in range(0,len(I1),1):
        Q1.append(I1[i] * t[i])

    #print Q1

    #H2理论值
    Vl = []
    for i in range(0,len(Q1),1):
        Vl.append((273.16 + T) * Q1[i] * 22400 /(273.16 * 2 * F))

    #phylab.RoundOne(Vl,3)
    #print Vl

    #相对误差
    A = []
    for i in range(0,len(Vc),1):
        A.append( (Vc[i] - Vl[i]) / Vl[i])

    A1 = "%.2f" % (A[0]*100)
    A1 = A1 + "\%"
    A2 = "%.2f" % (A[1]*100)
    A2 = A2 + "\%"
    A3 = "%.2f" % (A[2]*100)
    A3 = A3 + "\%"

    #print A
    #print A1
    #print A2
    #print A3


    #实验2
    #U2[] 输出电压，单位：V，（长度11）
    #I2[] 输出电流，单位：mA，（长度11）

    #P2[] 功率，单位：mW，（长度11）
    #Pm2 最大输出功率 单位：mW
    #n 燃料电池最大效率

    font = {
        'size' : 30 ,
    }

    fig1 = plt.figure(figsize=(15,9))
    pic1 = name+'_pic1'
    plt.plot(I2 , U2)
    plt.xlabel('I' , font)
    plt.ylabel('U' , font)
    fig1.savefig(pic1+'.png',bbox_inches='tight')

    I = 300 #电解池输入电流，计算效率

    P2 = []
    for i in range(0,len(U2),1):
        P2.append( U2[i] * I2[i] )

    Pm2 = max(P2)

    n = Pm2 / (1.48 * I)
    ET = "%.2f" % (n*100)
    ET =  ET + "\%"

    #print P2
    #print Pm2
    #print n
    #print ET


    #实验3
    #U3[] 输出电压，单位：V，（长度16）
    #I3[] 输出电流，单位：A，（长度16）
    #Isc 短路电流，单位：A
    #Uoc 开路电压，单位：V

    #P3[] 功率，单位：W，（长度16）
    #Pm3 最大输出功率 单位：W
    #FF 填充因子

    fig2 = plt.figure(figsize=(15,9))
    pic2 = name+'_pic2'
    plt.plot(U3 , I3)
    plt.xlabel('U' , font)
    plt.ylabel('I' , font)
    fig2.savefig(pic2+'.png',bbox_inches='tight')

    P3 = []
    for i in range(0,len(U3),1):
        P3.append( U3[i] * I3[i] )

    Pm3 = max(P3)


    fig3 = plt.figure(figsize=(15,9))
    pic3 = name+'_pic3'
    plt.plot(U3 , P3)
    plt.xlabel('U' , font)
    plt.ylabel('P' , font)
    fig3.savefig(pic3+'.png',bbox_inches='tight')

    index_m = P3.index(Pm3)
    Um3 = U3[index_m]
    Im3 = I3[index_m]

    FF = Pm3 / (Uoc * Isc)

    #print P3
    #print Pm3
    #print FF

    Q1_b = []
    Vl_b = []
    A_b = []
    P2_b = []
    P3_b = []
    FF_b = "%.3f" % FF

    for i in range (0 , len(Q1) , 1):
        Q1_b.append( "%.2f" % Q1[i] )
    for i in range (0 , len(Vl) , 1):
        Vl_b.append( "%.3f" % Vl[i] )
    for i in range (0 , len(A) , 1):
        A_b.append( "%.3f" % A[i] )
    for i in range (0 , len(P2) , 1):
        P2_b.append( "%.3f" % P2[i] )
    for i in range (0 , len(P3) , 1):
        P3_b.append( "%.3f" % P3[i] )

    return env.from_string(source).render(
            pic1 = pic1,
            pic2 = pic2 ,
            pic3 = pic3 ,
            I = I1,
            t = t,
            U1 = U1,
            T = T,
            It = Q1_b,
            Vc = Vc,
            VH = Vl_b,
            A = A_b,
            U = U2,
            I2 = I2,
            P =  P2_b,
            P_m = Pm2,
            u = U3,
            i = I3,
            p =  P3_b,
            I_sc = Isc,
            U_oc = Uoc,
            p_m = Pm3,
            U_m = Um3,
            I_m = Im3,
            FF =FF_b
            )


def handler(XML):
    file_object = open(texdir + "Handle2140113.tex" , "r", encoding='utf-8')
    source = file_object.read()
    file_object.close()
    data = readXml2140113(XML)
    return FuelCell(data[0][0] , data[0][1] , data[0][2] , data[0][3] , data[3][0][2] , data[1][0] , data[1][1] , data[2][0] , data[2][1] , data[3][0][0] , data[3][0][1] , source , sys.argv[3])

if __name__ == '__main__':
    scriptdir = '/home/zbw/Git/Phylab/Phylab/storage/app/script/'
    texdir = scriptdir + 'tex/'
    root = ''
    dom=xml.dom.minidom.parse(scriptdir + 'test/2140113test/2140113.xml')
    root = dom.documentElement
    print(handler(root))

#FuelCell([0.10,0.20,0.30],[1.984,2.10,2.59],[536,257,170],[6,6,6],18.8,[0.99,0.90,0.85,0.80,0.75,0.70,0.65,0.60,0.55,0.50,0.45],[0,6.7,24.3,64.4,137.0,191.3,229,255,256,256,257],
#        [0.0,0.2,0.4,0.6,0.8,1.0,1.2,1.4,1.6,1.8,2.0,2.2,2.4,2.6,2.8,3.0],
#        [0.315,0.314,0.313,0.311,0.310,0.309,0.308,0.307,0.306,0.305,0.304,0.303,0.298,0.285,0.253,0.165],0.315,3.19)
