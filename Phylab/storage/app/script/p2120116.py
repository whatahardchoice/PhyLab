# -*- coding: utf-8 -*-
from math import *
from jinja2 import Environment
from handler import texdir
from handler import scriptdir
import xml.dom.minidom
import copy
from phylab import BitAdapt
#texdir = "./tex/"
env = Environment()

def angletonum(list):
  l = len(list)
  for i in range(l):
    tmp = modf(list[i])
    tmp1 = tmp[0]/0.6
    list[i] = tmp[1] + tmp1
  return list


def ToScience(number):
    Tempstr = number
    # 如果发现Tempstr中含有e的话，说明是科学计数法
    if 'e' in Tempstr:
        index_str = Tempstr.split('e')
        if (index_str[1][0] == '+'):
            index_str[1] = index_str[1][1:]
        if index_str[0] == '1':
            return '10^{' + str(int(index_str[1])) + '}'
        else:
            return index_str[0] + '{\\times}10^{' + str(int(index_str[1])) + '}'
    else:
        return Tempstr


def angletorad(x):
  return radians(x)


def readXml2121(root):
  table_list = root.getElementsByTagName("table")
  
  data = []

  table1 = table_list[0]
  datalist = table1.getElementsByTagName("td")
  data0 = [float(datalist[0].firstChild.nodeValue),float(datalist[4].firstChild.nodeValue),float(datalist[8].firstChild.nodeValue),float(datalist[12].firstChild.nodeValue),float(datalist[16].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[1].firstChild.nodeValue),float(datalist[5].firstChild.nodeValue),float(datalist[9].firstChild.nodeValue),float(datalist[13].firstChild.nodeValue),float(datalist[17].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[2].firstChild.nodeValue),float(datalist[6].firstChild.nodeValue),float(datalist[10].firstChild.nodeValue),float(datalist[14].firstChild.nodeValue),float(datalist[18].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[3].firstChild.nodeValue),float(datalist[7].firstChild.nodeValue),float(datalist[11].firstChild.nodeValue),float(datalist[15].firstChild.nodeValue),float(datalist[19].firstChild.nodeValue)]
  data.append(data0)
  #---------------------------------光栅常数-----------------------------------
  table2 = table_list[1]
  datalist = table2.getElementsByTagName("td")
  data0 = [float(datalist[0].firstChild.nodeValue),float(datalist[1].firstChild.nodeValue),float(datalist[2].firstChild.nodeValue),float(datalist[3].firstChild.nodeValue)]
  data.append(data0)
  #------------------------------------------------------------------
  table3 = table_list[2]
  datalist = table3.getElementsByTagName("td")
  data0 = [float(datalist[0].firstChild.nodeValue),float(datalist[4].firstChild.nodeValue),float(datalist[8].firstChild.nodeValue),float(datalist[12].firstChild.nodeValue),float(datalist[16].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[1].firstChild.nodeValue),float(datalist[5].firstChild.nodeValue),float(datalist[9].firstChild.nodeValue),float(datalist[13].firstChild.nodeValue),float(datalist[17].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[2].firstChild.nodeValue),float(datalist[6].firstChild.nodeValue),float(datalist[10].firstChild.nodeValue),float(datalist[14].firstChild.nodeValue),float(datalist[18].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[3].firstChild.nodeValue),float(datalist[7].firstChild.nodeValue),float(datalist[11].firstChild.nodeValue),float(datalist[15].firstChild.nodeValue),float(datalist[19].firstChild.nodeValue)]
  data.append(data0)
  #-----------------------------------------------红光-----------------
  table4 = table_list[3]
  datalist = table4.getElementsByTagName("td")
  data0 = [float(datalist[0].firstChild.nodeValue),float(datalist[4].firstChild.nodeValue),float(datalist[8].firstChild.nodeValue),float(datalist[12].firstChild.nodeValue),float(datalist[16].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[1].firstChild.nodeValue),float(datalist[5].firstChild.nodeValue),float(datalist[9].firstChild.nodeValue),float(datalist[13].firstChild.nodeValue),float(datalist[17].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[2].firstChild.nodeValue),float(datalist[6].firstChild.nodeValue),float(datalist[10].firstChild.nodeValue),float(datalist[14].firstChild.nodeValue),float(datalist[18].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[3].firstChild.nodeValue),float(datalist[7].firstChild.nodeValue),float(datalist[11].firstChild.nodeValue),float(datalist[15].firstChild.nodeValue),float(datalist[19].firstChild.nodeValue)]
  data.append(data0)
  #--------------------------------------------蓝光-------------------

  table5 = table_list[4]
  datalist = table5.getElementsByTagName("td")
  data0 = [float(datalist[0].firstChild.nodeValue),float(datalist[4].firstChild.nodeValue),float(datalist[8].firstChild.nodeValue),float(datalist[12].firstChild.nodeValue),float(datalist[16].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[1].firstChild.nodeValue),float(datalist[5].firstChild.nodeValue),float(datalist[9].firstChild.nodeValue),float(datalist[13].firstChild.nodeValue),float(datalist[17].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[2].firstChild.nodeValue),float(datalist[6].firstChild.nodeValue),float(datalist[10].firstChild.nodeValue),float(datalist[14].firstChild.nodeValue),float(datalist[18].firstChild.nodeValue)]
  data.append(data0)

  data0 = [float(datalist[3].firstChild.nodeValue),float(datalist[7].firstChild.nodeValue),float(datalist[11].firstChild.nodeValue),float(datalist[15].firstChild.nodeValue),float(datalist[19].firstChild.nodeValue)]
  data.append(data0)
  #---------------------------------------------紫光-------------------------
  #data = map(angletonum,data)
  

  return data
  #data[0]为a1的-1级，data[1]为B1的-1级，data[2]为a2的+1级，data[3]为B2的+1级
  #data[4]为a1,B1的-2级和a2,B2的+2级
  #data0-4 为光栅常数的
  #data 5-8为红光的，data 9-12为蓝光的 data 13-16为紫光的 

def SteelWire(data,source):

  data2 = copy.deepcopy(data)
  l = len(data)
  data1 = []
  for i in range(l):
    data1.append(angletonum(data2[i]))

  d_theta1 = []
  for i in range(5):
    tmp = ((data1[0][i]-data1[2][i]) + (data1[1][i]-data1[3][i]) )/2
    if tmp >= 0:
      d_theta1.append(tmp)
    else:
      d_theta1.append(tmp+180)

  ave_d_theta1 = sum(d_theta1)/5
  ave_theta1 = ave_d_theta1/2
  ave_theta1 = angletorad(ave_theta1)


  d_theta2 = ( (data1[4][0]-data1[4][2]) + (data1[4][1]-data1[4][3]) )/2
  theta2 = angletorad(d_theta2/2)

  d = 589.3/sin(ave_theta1)/1000
  d1 = 2*589.3/sin(theta2)/1000

  d_theta1_tmp = []
  for i in range(5):
    d_theta1_tmp.append((d_theta1[i]-ave_d_theta1)*(d_theta1[i]-ave_d_theta1))

  ua_ave_d_theta1 = sqrt(sum(d_theta1_tmp)/20)
  ua_ave_d_theta1 = angletorad(ua_ave_d_theta1)

  ub_ave_d_theta1 = 1/sqrt(3)/60
  ub_ave_d_theta1 = angletorad(ub_ave_d_theta1)

  u_ave_d_theta1 = hypot(ua_ave_d_theta1,ub_ave_d_theta1)
  u_ave_theta1 = u_ave_d_theta1/2

  u_d_d = sqrt(pow(u_ave_theta1/tan(ave_theta1),2))
  u_d = d*sqrt(pow(u_ave_theta1/tan(ave_theta1),2))

  u_d_theta2 = 1/sqrt(3)/60
  u_theta2 = angletorad(u_d_theta2/2)

  u_d1_d1 = sqrt(pow(u_theta2/tan(theta2),2))
  u_d1 = d1* sqrt(pow(u_theta2/tan(theta2),2))

  ave_d = (d/pow(u_d,2) + d1/pow(u_d1,2)) / (1/pow(u_d,2) + 1/pow(u_d1,2))
  u_ave_d2 = 1/(1/pow(u_d,2) + 1/pow(u_d1,2))
  u_ave_d = sqrt(u_ave_d2)

  d_thetar = []
  for i in range(5):
    tmp = ( (data1[5][i]-data1[7][i]) + (data1[6][i]-data1[8][i]) )/2
    if tmp >= 0:
      d_thetar.append(tmp)
    else:
      tmp = 180 + tmp
      d_thetar.append(tmp)
  ave_d_thetar = sum(d_thetar) / 5

  d_thetar_tmp = []
  for i in range(5):
    d_thetar_tmp.append((d_thetar[i]-ave_d_thetar)*(d_thetar[i]-ave_d_thetar))


  ave_d_thetar = angletorad(ave_d_thetar)

  ave_thetar = ave_d_thetar/2
  Bo_r = ave_d*sin(ave_thetar)*1000
  R_H1 = 1000000000/(Bo_r*(1.0/4-1.0/9))


  ua_ave_d_thetar = sqrt(sum(d_thetar_tmp)/20)
  ua_ave_d_thetar = angletorad(ua_ave_d_thetar)
  ub_ave_d_thetar = 1/sqrt(3)/60
  ub_ave_d_thetar = angletorad(ub_ave_d_thetar)
  u_ave_d_thetar = hypot(ua_ave_d_thetar,ub_ave_d_thetar)
  u_ave_thetar = u_ave_d_thetar/2

  u_R_H1_H1 = hypot(u_ave_d/ave_d,u_ave_thetar/tan(ave_thetar))
  u_R_H1 = R_H1*hypot(u_ave_d/ave_d,u_ave_thetar/tan(ave_thetar))
  #--------------------------------------------------------------------#
  d_thetab = []
  for i in range(5):
    tmp = ( (data1[9][i]-data1[11][i]) + (data1[10][i]-data1[12][i]) )/2
    if tmp >= 0:
      d_thetab.append(tmp)
    else:
      tmp = 180 + tmp
      d_thetab.append(tmp)


  ave_d_thetab = sum(d_thetab)/5

  d_thetab_tmp = []
  for i in range(5):
    d_thetab_tmp.append((d_thetab[i]-ave_d_thetab)*(d_thetab[i]-ave_d_thetab))

  ave_d_thetab = angletorad(ave_d_thetab)

  ave_thetab = ave_d_thetab/2
  Bo_b = ave_d*sin(ave_thetab)*1000
  R_H2 = 1/(Bo_b*(1.0/4-1.0/16))*1000000000



  ua_ave_d_thetab = sqrt(sum(d_thetab_tmp)/20)
  ua_ave_d_thetab = angletorad(ua_ave_d_thetab)
  ub_ave_d_thetab = 1/sqrt(3)/60
  ub_ave_d_thetab = angletorad(ub_ave_d_thetab)
  u_ave_d_thetab = hypot(ua_ave_d_thetab,ub_ave_d_thetab)
  u_ave_thetab = u_ave_d_thetab/2

  u_R_H2_H2 = hypot(u_ave_d/ave_d,u_ave_thetab/tan(ave_thetab))
  u_R_H2 = R_H2*hypot(u_ave_d/ave_d,u_ave_thetab/tan(ave_thetab))
  #---------------------------------------------------------------------#
  d_thetap = []
  for i in range(5):
    tmp = ( (data1[13][i]-data1[15][i]) + (data1[14][i]-data1[16][i]) )/2
    if tmp >= 0:
      d_thetap.append (tmp)
    else:
      d_thetap.append(tmp+180)

  ave_d_thetap = sum(d_thetap)/5

  d_thetap_tmp = []
  for i in range(5):
    d_thetap_tmp.append((d_thetap[i]-ave_d_thetap)*(d_thetap[i]-ave_d_thetap))
  ave_d_thetap = angletorad(ave_d_thetap)

  ave_thetap = ave_d_thetap/2
  Bo_p = ave_d*sin(ave_thetap)*1000
  R_H3 = 1/(Bo_p*(1.0/4-1.0/25))*1000000000



  ua_ave_d_thetap = sqrt(sum(d_thetap_tmp)/20)
  ua_ave_d_thetap = angletorad(ua_ave_d_thetap)
  ub_ave_d_thetap = 1/sqrt(3)/60
  ub_ave_d_thetap = angletorad(ub_ave_d_thetap)
  u_ave_d_thetap = hypot(ua_ave_d_thetap,ub_ave_d_thetap)
  u_ave_thetap = u_ave_d_thetap/2

  u_R_H3_H3 = hypot(u_ave_d/ave_d,u_ave_thetap/tan(ave_thetap))
  u_R_H3 = R_H3*hypot(u_ave_d/ave_d,u_ave_thetap/tan(ave_thetap))
  #---------------------------------------------------------------------
  ave_R_H = (R_H1/pow(u_R_H1,2) + R_H2/pow(u_R_H2,2) + R_H3/pow(u_R_H3,2))/(1/pow(u_R_H1,2) + 1/pow(u_R_H2,2) + 1/pow(u_R_H3,2))
  u_ave_R_H = sqrt(1/(1/pow(u_R_H1,2) + 1/pow(u_R_H2,2) + 1/pow(u_R_H3,2)))

  N = 2.2/ave_d*1000
  R1 = N
  R2 = 2*N
  D_theta1 = 1/(ave_d*sin(ave_theta1))
  D_theta2 = 2/(ave_d*sin(theta2))

  L_theta1 = asin(589/ave_d/1000)
  L_theta2 = asin(589.6/ave_d/1000)

  theta12 = L_theta2 - L_theta1

  theta0 = asin(589.3/2.2/10000000)

  #final = phylab.BitAdapt(ave_R_H,u_ave_R_H)
  return env.from_string(source).render(
    data = data,
    d_theta1 = map(lambda x:'%.3f'%(x), d_theta1),
    ave_d_theta1 = '%.3f'%(ave_d_theta1),
    ave_theta1 = '%.4f'%(ave_theta1),
    d_theta2 = '%.2f'%(d_theta2),
    theta2 = '%.4f'%(theta2),
    d = '%.3f'%(d),
    d1 = '%.3f'%(d1),
    ua_ave_d_theta1 = '%.6f'%(ua_ave_d_theta1),
    ub_ave_d_theta1 = '%.6f'%(ub_ave_d_theta1),
    u_ave_theta1 = ToScience('%.3e'%(u_ave_theta1)),
    u_ave_d_theta1 = '%.6f'%(u_ave_d_theta1),
    u_d_d = ToScience('%.3e'%(u_d_d)),
    u_d = '%.5f'%(u_d),
    d_u_d = BitAdapt(d, u_d),
    u_d_theta2 = '%.6f'%(u_d_theta2),
    u_theta2 = ToScience('%.3e'%(u_theta2)),
    u_d1 = '%.7f'%(u_d1),
    u_d1_d1 = ToScience('%.3e'%(u_d1_d1)),
    d1_u_d1 = BitAdapt(d1, float('%.3f'%(u_d1))),
    ave_d = '%.3f'%(ave_d),
    u_ave_d = ToScience('%.1e'%(u_ave_d)),
    ave_d_u_ave_d = BitAdapt(ave_d, float('%.3f'%(u_ave_d))),
    u_ave_d2 = ToScience('%.3e'%(u_ave_d2)),
    d_thetar = map(lambda x:'%.3f'%(x), d_thetar),
    ave_d_thetar = '%.4f'%(ave_d_thetar),
    ave_thetar = ave_thetar,
    thetar_u_thatar = BitAdapt(ave_thetar,u_ave_thetar),
    Bo_r = '%.3f'%(Bo_r),
    R_H1 = ToScience('%.6e'%(R_H1)),
    ua_ave_d_thetar = ToScience('%.4e'%(ua_ave_d_thetar)),
    ub_ave_d_thetar = ToScience('%.4e'%(ub_ave_d_thetar)),
    u_ave_d_thetar = ToScience('%.4e'%(u_ave_d_thetar)),
    u_ave_thetar = ToScience('%.4e'%(u_ave_thetar)),
    u_R_H1_H1 = ToScience('%.4e'%(u_R_H1_H1)),
    u_R_H1 = ToScience('%.8e'%(u_R_H1)),
    R_H1_u_R_H1 = BitAdapt(R_H1, u_R_H1),
    d_thetab = map(lambda x:'%.3f'%(x), d_thetab),
    ave_d_thetab = '%.5f'%(ave_d_thetab),
    ave_thetab = '%.5f'%(ave_thetab),
    ua_ave_d_thetab = ToScience('%.5e'%(ua_ave_d_thetab)),
    ub_ave_d_thetab = ToScience('%.5e'%(ub_ave_d_thetab)),
    u_ave_d_thetab = ToScience('%.5e'%(u_ave_d_thetab)),
    u_ave_thetab = ToScience('%.5e'%(u_ave_thetab)),
    thetab_u_thatab = BitAdapt(ave_thetab,u_ave_thetab),
    Bo_b ='%.3f'%(Bo_b),
    R_H2 = ToScience('%.4e'%(R_H2)),
    u_R_H2 = ToScience('%.7e'%(u_R_H2)),
    u_R_H2_H2 = ToScience('%.4e'%(u_R_H2_H2)),
    R_H2_u_R_H2 = BitAdapt(R_H2, u_R_H2),
    d_thetap = map(lambda x:'%.3f'%(x), d_thetap),
    ave_d_thetap = '%.5f'%(ave_d_thetap),
    ave_thetap = '%.5f'%(ave_thetap),
    thetap_u_thatap = BitAdapt(ave_thetap,u_ave_thetap),
    Bo_p = '%.5f'%(Bo_p),
    R_H3 = ToScience('%.6e'%(R_H3)),
    u_R_H3 = ToScience('%.8e'%(R_H3)),
    u_R_H3_H3 = ToScience('%.4e'%(u_R_H3_H3)),
    R_H3_u_R_H3 = BitAdapt(R_H3, u_R_H3),
    ua_ave_d_thetap = ToScience('%.4e'%(ua_ave_d_thetap)),
    u_ave_d_thetap = ToScience('%.4e'%(u_ave_d_thetap)),
    u_ave_thetap = ToScience('%.4e'%(u_ave_thetap)),
    ave_R_H = ToScience('%.4e'%(ave_R_H)),
    u_ave_R_H = '%.8f'%(u_ave_R_H),
    R_H_u_R_H = BitAdapt(ave_R_H, u_ave_R_H),
    N = '%.2f'%(N),
    D_theta1 = ToScience('%.5e'%(D_theta1)),
    D_theta2 = ToScience('%.5e'%(D_theta2)),
    L_theta1 = '%.3f'%(L_theta1),
    L_theta2 = '%.3f'%(L_theta2),
    theta12 = '%.6f'%(theta12),
    theta0 = '%.6f'%(theta0),
    R1 = '%.2f'%(R1),
    R2 = '%.2f'%(R2),
      )

def handler(XML):
  file_object = open(texdir + "Handle2120116.tex","r")
  #将模板作为字符串存储在template文件中
  source = file_object.read().decode('utf-8', 'ignore')
  file_object.close()
  data = readXml2121(XML)
  return SteelWire(data, source)
  
if __name__ == '__main__':
  scriptdir = 'D:/Apache24/htdocs/PhyLabs/Phylab/storage/app/script/'
  texdir = scriptdir + 'tex/'
  dom = xml.dom.minidom.parse(scriptdir + 'test/2120116test/2120116.xml')
  root = dom.documentElement
  print handler(root)