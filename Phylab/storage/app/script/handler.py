#! /usr/bin/python
# -*- coding: utf-8 -*-

import subprocess
import sys
import traceback
import os
import platform
import xml.dom.minidom

scriptdir = '/var/www/Phylab/storage/app/script/' if platform.system() == 'Linux' else 'C:/Users/CFREE/Documents/Github/auto-deploy-phylab/Phylab/storage/app/script/'
texdir = scriptdir + 'tex/'
sys.path.append(scriptdir)

if __name__ == '__main__':
    # 三个命令行参数
    # 第一个argv[1]是实验编号，如1010113
    # 第二个argv[2]是XML文件所在地址，采用绝对路径
    # 第三个argv[3]是最终生成的pdf文件的目标地址，采用绝对路径


    try:
        latex_head_file = open(texdir + 'Head.tex', 'r', encoding='utf-8')
        latex_head = latex_head_file.read()
        latex_head_file.close();
        latex_tail = "\n\end{CJK*}\n\\end{document}"
        latex_body = ""

        try:
            root = ''
            dom = xml.dom.minidom.parse(sys.argv[2])
            root = dom.documentElement
        except Exception as e:
            # raise e
            pass
        latex_body = __import__('p' + sys.argv[1]).handler(root)  # (sys.argv[2])
        if (not os.path.isfile(scriptdir + 'p' + sys.argv[1] + '.py')):
            print('{"status":"fail", "msg":"no handler"}')
            exit(1)

        finish_str = latex_head + latex_body + latex_tail
        finish_file = open(sys.argv[3] + ".tex", "w", encoding='utf-8')
        finish_file.write(finish_str)
        finish_file.close()

        os.chdir(os.path.dirname(sys.argv[3]))
        # 等于１时是错误

        ret = subprocess.call("/usr/local/texlive/2018/bin/x86_64-linux/pdflatex -interaction=nonstopmode " + sys.argv[3] + ".tex", shell=True)
        subprocess.call("rm " + sys.argv[3] + ".aux", shell=True)
        # file not exist
        #subprocess.call("rm " + sys.argv[3] + ".synctex*", shell=True)
        subprocess.call("rm " + sys.argv[3] + ".log", shell=True)

        if ret == 0:
            print('{"status":"success"}')
        else:
            print('{"status":"fail", "msg":"fail to handle"}')
            exit(ret)

    except Exception as e:
        # print(e.getTraceAsString())
        exstr = traceback.format_exc()
        print(exstr)
        # raise e
        print('{"status":"fail", "msg":"exception"}')
        exit(1)
