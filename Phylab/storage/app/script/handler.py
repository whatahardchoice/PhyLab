#! /usr/bin/python
# -*- coding: utf-8 -*-

import subprocess
import sys
import traceback
import os
import platform
import xml.dom.minidom

available_lab = ['1010113', '1010212', '1020113', '1060111', '1060213', '1070212', '1070312', '1070322', '1080114', '1080124', '1080215', '1080225', '1090114']
xmlid = {'1010113': '', '1010212': '', '1070212': '10711', '1070312': '10712', '1070322': '', '1080114': '10811',
         '1080124': '', '1090114': ''}
handledir = '/var/www/buaaphylab/storage/app/script/' if platform.system() == 'Linux' else 'C:/Users/CFREE/Documents/Github/auto-deploy-phylab/Phylab/storage/app/script/'
texdir = handledir + 'tex/'
sys.path.append(handledir)

if __name__ == '__main__':
    # 三个命令行参数
    # 第一个argv[1]是实验编号，如1010113
    # 第二个argv[2]是XML文件所在地址，采用绝对路径
    # 第三个argv[3]是最终生成的pdf文件的目标地址，采用绝对路径


    try:
        latex_head_file = open(texdir + 'Head.tex', 'r')
        latex_head = latex_head_file.read().decode('utf-8', 'ignore')
        latex_head_file.close();
        latex_tail = "\n\\end{document}"
        latex_body = ""

        flag = True
        for lab in available_lab:
            if (lab == sys.argv[1]):
                # from p1010113 import handler
                # eval('from p' + lab + ' import handler')
                # testxml = handledir + 'test/' + lab + 'test/' + lab + '.xml'
                try:
                    root = ''
                    dom = xml.dom.minidom.parse(sys.argv[2])
                    root = dom.documentElement
                # sublab_list = root.getElementsByTagName('sublab')
                # for l in sublab_list:
                # 	if (l.getAttribute("status") == 'true') & (l.getAttribute("id") == xmlid[lab]):
                # 		sublab = l
                except Exception as e:
                    # raise e
                    pass
                latex_body = __import__('p' + lab).handler(root)  # (sys.argv[2])
                flag = False
                break
        if (flag):
            print('{"status":"fail", "msg":"no handler"}')
            exit(1)

        finish_str = latex_head + latex_body + latex_tail
        finish_file = open(sys.argv[3] + ".tex", "w")
        finish_file.write(finish_str.encode('utf-8', 'ignore'))
        finish_file.close()

        os.chdir(os.path.dirname(sys.argv[3]))
        # 等于１时是错误
        ret = subprocess.call("pdflatex -interaction=nonstopmode " + sys.argv[3] + ".tex", shell=True)
        subprocess.call("rm " + sys.argv[3] + ".aux", shell=True)
        subprocess.call("rm " + sys.argv[3] + ".synctex*", shell=True)
        subprocess.call("rm " + sys.argv[3] + ".log", shell=True)

        if ret == 0:
            print('{"status":"success"}')
        else:
            print('{"status":"fail", "msg":"fail to handle"}')
            exit(1)

    except Exception as e:
        # print(e.getTraceAsString())
        exstr = traceback.format_exc()
        print exstr
        # raise e
        print('{"status":"fail", "msg":"exception"}')
        exit(1)
