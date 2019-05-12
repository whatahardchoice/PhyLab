# -*- coding: utf-8 -*-

import phylab
from math import sqrt
from jinja2 import Environment
from handler import texdir
from handler import scriptdir
import xml.dom.minidom

env = Environment(line_statement_prefix="#", variable_start_string="%%", variable_end_string="%%")

LOCA = []
LEN = 0.0
WID = 0.0
THIC = 0.0
LASER = 0.0
WEIGHT = 0.0

FY = 0.0
LEN3 = 0.0
TEMP_RES_1 = 0.0
YI = []
XIYI = []
A = 0.0
E = 0.0
SY = 0.0
SA = 0.0
UE = 0.0
E_INT = 0.0
ETA = 0.0


def readXml2110114(root):
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

def Holograph (loca,l,b,h,wl,m,source):
    #loca为一维数组，长度为8 单位：cm
    #l为铝板长，单位：mm
    #b为铝板宽，单位：mm
    #h为铝板厚，单位：mm
    #wl为激光波长，单位：nm
    #m为砝码质量，单位：g


    LOCA = loca
    WEIGHT = m
    FY = WEIGHT*9.8*0.001
    LEN = l*0.001
    LEN3 = LEN*3
    LASER = wl*1e-9
    WID = b*0.001
    THIC = h*0.001

    print(FY)
    print(LEN)
    print(WID)
    print(LASER)
    print(THIC)

    TEMP_RES_1 = 8*FY/LASER/WID/(pow(THIC,3))
    print(TEMP_RES_1)

    X = []
    for i in range(1,9,1):
        X.append(2 * i - 1)

    xi_n = [1,3,5,7,9,11,13,15,64,8]

    X.append(sum(X)/len(X))

    Y = []
    for i in range(0,len(loca),1):
        Y.append((LEN3 - loca[i] * pow(10,-2)) * pow(loca[i],2) * pow(10,-4))


    yi_n = [i for i in Y]
    yi_n.append(sum(Y))
    yi_n.append(sum(Y)/len(Y))
    YI = [phylab.ToScience(i) for i in yi_n]

    XIYI = [phylab.ToScience(i*j) for i,j in zip(xi_n, yi_n)]


    Y.append(sum(Y)/len(Y))



    #一元线性回归计算
    res = phylab.ULR(X,Y)
    A = res[0]

    E = 8 * A * m * 9.8 * pow(10,9) / (wl * b * pow(h,3))

    #求不确定度
    S = 0
    X_2 = []

    size = len(X)-1

    for i in range(size):
        X_2.append(X[i]**2)
        S += (Y[i] - A * X[i])**2

    X_2.append(sum(X_2)/size)

    ua_A = sqrt(S / ((size - 2) * size * (X_2[size] - X[size]**2)))
    SY = S
    u_A = ua_A
    SA = u_A

    u_E = 8 * u_A * m * 9.8 * pow(10,9) / (wl * b * pow(h,3))
    UE = round(u_E)


    final = phylab.BitAdapt(E,u_E)

    #求相对误差
    n = abs((E - 70)/70)
    ETA = "%.2f" % (n*100)
    ETA =  ETA+"\%"

    print(X)
    print(Y)
    print(A)
    print(E)
    print(u_A)
    print(u_E)
    print(final)
    print(ETA)


    return env.from_string(source).render(
        LOCA = LOCA,
        LEN_O = l,
        WID_O = b,
        THIC_O = h,
        LASER_O = wl,
        WEIGHT_O = m,
        LEN = LEN,
        WID = WID,
        THIC = THIC,
        LASER = phylab.ToScience(LASER),
        WEIGHT=WEIGHT,
        FY = FY,
        LEN3 = LEN3,
        TEMP_RES_1 = phylab.ToScience(TEMP_RES_1),
        YI = YI,
        XIYI = XIYI,
        A = phylab.ToScience(A),
        E = phylab.ToScience(E),
        SA = phylab.ToScience(SA),
        SY = phylab.ToScience(SY),
        E_INT = int(round(E)),
        UE = int(round(UE)),
        ETA = ETA)

        # E = phylab.ToScience(E),
        # u_B = phylab.ToScience(u_B),
        # u_E = phylab.ToScience(u_E),
        # final = final,
        # n = n
        # )



def handler(XML, type):
    if type == 1:
        file_object = open(texdir + "Handle2110114.tex" , "r",encoding='utf-8')
    else:
        file_object = open(texdir + "Handle2110114.md" , "r",encoding='utf-8')
    source = file_object.read()
    file_object.close()
    data = readXml2110114(XML)
    return Holograph(data[0][0] , data[1][0][0] , data[1][0][1] , data[1][0][2] , data[1][0][3] , data[1][0][4] , source)

if __name__ == '__main__':
    scriptdir = '/home/zbw/Git/Phylab/Phylab/storage/app/script/'
    texdir = scriptdir + 'tex/'
    root = ''
    dom=xml.dom.minidom.parse(scriptdir + 'test/2110114test/2110114.xml')
    root = dom.documentElement
    print(handler(root))

#Holograph([0,0.35,0.63,0.92,1.12,1.30,1.41,1.63],70,40,1.54,632.8,10)
