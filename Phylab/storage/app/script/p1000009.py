# -*- coding: utf-8 -*-

import xml.dom.minidom
#from math import sqrt
from jinja2 import Environment
from handler import texdir
#from handler import scriptdir

#原始数据
zidong_dianjisudu=[]
zidong_duopulesudu=[]
zidong_wucha=[]

xiuzheng_dianjisudu=[]
xiuzheng_duopulesudu=[]
xiuzheng_wucha=[]
xiuzheng_wendu=[]

shoudong_wendu=[]
shoudong_pinlv=[]
shoudong_dianjisudu=[]
shoudong_guangdian=[]
shoudong_pinyi=[]

shengsu_shijian=[]
shengsu_changdu=[1.58]

#数据处理得到的数据

zidong_pingjunwucha=[]

xiuzheng_shengsu=[]
xiuzheng_pingjunwucha=[]


shoudong_shengsu=[]
shoudong_guangdiansudu=[]
shoudong_duopulesudu=[]
shoudong_wucha=[]

shengsu_shengsu=[]

vars_original_4="zidong_dianjisudu,xiuzheng_dianjisudu,shoudong_dianjisudu".split(",") #有4个值的原始数据及其保留位数

vars_original_8="zidong_duopulesudu,zidong_wucha,xiuzheng_duopulesudu,xiuzheng_wucha,shoudong_guangdian,shoudong_pinyi".split(",")#有8个值的数据
vars_original_1="xiuzheng_wendu,shoudong_wendu,shoudong_pinlv,shengsu_shijian".split(",")#有1个值的数据

vars_secondhand_4="zidong_pingjunwucha,xiuzheng_pingjunwucha".split(",")#有4个值的处理得到得到数据

vars_secondhand_8="shoudong_guangdiansudu,shoudong_duopulesudu,shoudong_wucha".split(",")#有8个值的数据

vars_secondhand_1="xiuzheng_shengsu,shoudong_shengsu,shengsu_shengsu".split(",")#有一个值的数据

vars_digits={"zidong_dianjisudu":0,"xiuzheng_dianjisudu":0,"shoudong_dianjisudu":0,"zidong_duopulesudu":4,"zidong_wucha":4,"xiuzheng_duopulesudu":4,"xiuzheng_wucha":4,"shoudong_guangdian":2,"shoudong_pinyi":2,"xiuzheng_wendu":2,"shoudong_wendu":2,"shoudong_pinlv":2,"shengsu_shijian":2,"zidong_pingjunwucha":5,"xiuzheng_pingjunwucha":5,"shoudong_guangdiansudu":4,"shoudong_duopulesudu":4,"shoudong_wucha":4,"xiuzheng_shengsu":2,"shoudong_shengsu":2,"shengsu_shengsu":2,"shengsu_changdu":2}#保留位数

all_vars=[]
all_vars.extend(vars_original_4)
all_vars.extend(vars_original_8)
all_vars.extend(vars_original_1)
all_vars.extend(vars_secondhand_4)
all_vars.extend(vars_secondhand_8)
all_vars.extend(vars_secondhand_1) #应该都用字典的,弄得特别乱

shengsu_changdu[0]=1.58

env = Environment()

def readOnevar(root,name,num):
    exec("global"+" "+name)
#    print(name)
    node=root.getElementsByTagName(name)[0];
    if(num>1):
        for j in range(0,num):
#            print(node.childNodes[j].childNodes[0].nodeValue)
            exec(name+".append(float(node.childNodes[j].childNodes[0].nodeValue))")
    else:
#        print(node.childNodes[0].nodeValue)
        exec(name+".append(float(node.childNodes[0].nodeValue))")
    

def readXML2031(root):
    for i in vars_original_4:
        exec("global"+" "+i)
        readOnevar(root,i,4)
    for i in vars_original_8:
        exec("global"+" "+i)
        readOnevar(root,i,8)
    for i in vars_original_1:
        exec("global"+" "+i)
        readOnevar(root,i,1)
    
def regulation():
    for i in all_vars:
        exec("global "+i)
        str1='''
for j in range(0,len('''+i+''')):
    '''+i+'''[j]='%.'''+str(vars_digits[i])+"f'%"+i+"[j]"
        exec(str1)
        
def LatexFiller(latex_address):
    str1=""
    
    for i in all_vars:
        length=0
        exec("length=len("+i+")")
        if length>1: #含有超过1个值的数据
            str1=str1+i+"="+i+",\n"
        else:#仅含有一个值的数据
            str1=str1+i+"="+i+"[0],\n"
    str1=str1[0:-1] #删除最后一个','
    str1="result=env.from_string(latex_address).render("+str1+")"
    exec(str1)

    return result


def handler(XML):
    readXML2031(XML)

    xiuzheng_shengsu.append(xiuzheng_wendu[0]*0.6+331.45)#修正声速
    shoudong_shengsu.append(shoudong_wendu[0]*0.6+331.45)#手动声速
    shengsu_shengsu.append(1000*shengsu_changdu[0]/shengsu_shijian[0])#声速

    for i in range(0,4):
        zidong_pingjunwucha.append((zidong_wucha[2*i]+zidong_wucha[2*i+1])/2)#计算自动平均误差
        xiuzheng_pingjunwucha.append((xiuzheng_wucha[2*i]+xiuzheng_wucha[2*i+1])/2)#计算修正平均误差 
        
    for i in range(0,8):
        shoudong_guangdiansudu.append((1.0*10/shoudong_guangdian[i]))#计算光电门测得的速度
        if(i%2):
            shoudong_duopulesudu.append(round(shoudong_pinyi[i]/(2*shoudong_pinlv[0]*1000-shoudong_pinyi[i])*shoudong_shengsu[0],4))#靠近时频移测得速度
        else:
            shoudong_duopulesudu.append(round(shoudong_pinyi[i]/(2*shoudong_pinlv[0]*1000+shoudong_pinyi[i])*shoudong_shengsu[0],4))#远离时频移测得速度
        
        shoudong_wucha.append((shoudong_duopulesudu[i]-shoudong_guangdiansudu[i])/shoudong_guangdiansudu[i])#手动测量的相对误差

    regulation()

    latexaddress=texdir+"Handle1000009.tex"
    latexaddress=open("latexaddress","r").read().decode("utf-8")
#    print(latexaddress.decode("utf-8"))

    result=LatexFiller(latexaddress)
    return result