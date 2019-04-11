# -*- coding: utf-8 -*-

import phylab
from math import sqrt

def readXml2110114(root):
        table_list = root.getElementsByTagName("table")
        data = []
        for table in table_list:
                table_tr_list = table.getElementsByTagName("tr")
                trdara = []
                for tr in table_tr_list:
                        tr_td_list = tr.getElementsByTagName("td")
                        trdata += [map(lambda x: float(x.firstChild.nodeValue), tr_td_list)]
                data += [trdata]
        return data

def Holograph (loca,l,b,h,wl,m):
        #loca为一维数组，长度为8 单位：cm
        #l为铝板长，单位：mm
        #b为铝板宽，单位：mm
        #h为铝板厚，单位：mm
        #wl为激光波长，单位：nm
        #m为砝码质量，单位：g

        X = []
        for i in range(1,9,1):
            X.append(2 * i - 1)

        X.append(sum(X)/len(X))

        Y = []
        for i in range(0,len(loca),1):
            Y.append((3 * l * pow(10,-3) - loca[i] * pow(10,-2)) * pow(loca[i],2) * pow(10,-4))

        Y.append(sum(Y)/len(Y))

        #一元线性回归计算
        res = phylab.ULR(X,Y)
        B = res[0]

        E = 8 * B * m * 9.8 * pow(10,9) / (wl * b * pow(h,3))

        #求不确定度
        S = 0
        X_2 = []
        
        size = len(X)-1
        
        for i in range(size):
            X_2.append(X[i]**2)
            S += (Y[i] - B * X[i])**2

        X_2.append(sum(X_2)/size)

        ua_B = sqrt(S / ((size - 2) * size * (X_2[size] - X[size]**2)))

        u_B = ua_B

        u_E = 8 * u_B * m * 9.8 * pow(10,9) / (wl * b * pow(h,3))

        final = phylab.BitAdapt(E,u_E)

        #求相对误差
        n = abs((E - 70)/70)

        return final
        
        #print(X)
        #print(Y)
        #print(B)
        #print(E)
        #print(u_B)
        #print(u_E)
        #print(final)
        #print(n)

        
def handler(XML):
        file_object = open(texdir + "Handle2110114.tex" , "r")
        source = file_object.read().decode('utf-8', 'ignore')
	file_object.close()
	data = readXml2110114(XML)
	return Holograph(data[0][0] , data[1][0][0] , data[1][0][1] , data[1][0][2] , data[1][0][3] , data[1][0][4])

if __name__ == '__main__':
        scriptdir = 'D:/Apache24/htdocs/PhyLabs/Phylab/storage/app/script/'
        texdir = scriptdir + 'tex/'
        root = ''
        dom=xml.dom.minidom.parse(scriptdir + 'test/2110114test/2110114.xml')
        root = dom.documentElement
        print handler(root)
        
#Holograph([0,0.35,0.63,0.92,1.12,1.30,1.41,1.63],70,40,1.54,632.8,10)
