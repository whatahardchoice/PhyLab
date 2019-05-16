#! /usr/bin/python
# -*- coding: utf-8 -*-
# 这是生成markdown转为html的处理脚本

import sys
import traceback
import os
import platform
import xml.dom.minidom
import markdown
from mdx_math import MathExtension

scriptdir = '/var/www/Phylab/storage/app/script/' if platform.system() == 'Linux' else 'C:/Users/CFREE/Documents/Github/auto-deploy-phylab/Phylab/storage/app/script/'
mddir = scriptdir + 'markdown/'
sys.path.append(scriptdir)

if __name__ == '__main__':
    # 三个命令行参数
    # 第一个argv[1]是实验编号，如1010113
    # 第二个argv[2]是XML文件所在地址，采用绝对路径
    # 第三个argv[3]是最终生成的html文件的目标地址，采用绝对路径


    try:
        html_head_file = open(mddir + 'html_head.txt', 'r', encoding='utf-8')
        html_head = html_head_file.read()
        html_head_file.close()
        html_tail = "\n</body>\n</html>"
        html_body = ""

        try:
            root = ''
            dom = xml.dom.minidom.parse(sys.argv[2])
            root = dom.documentElement

        except Exception as e:
            #raise e
            pass

        html_body_txt = __import__('p' + sys.argv[1]).handler(root, 2)  # (sys.argv[2])
        if (not os.path.isfile(scriptdir + 'p' + sys.argv[1] + '.py')):
            print('{"status":"fail", "msg":"no handler"}')
            exit(1)

        exts = ['markdown.extensions.extra', 'markdown.extensions.codehilite','markdown.extensions.tables','markdown.extensions.toc',MathExtension(enable_dollar_delimiter=True)]

        md = markdown.Markdown(extensions=exts)
        html_body = md.convert(html_body_txt)

        finish_str = html_head + html_body + html_tail
        finish_file = open(sys.argv[3] + ".html", "w", encoding='utf-8')
        finish_file.write(finish_str)
        finish_file.close()
        print('{"status":"success"}')
        exit(0)

    except Exception as e:
        # print(e.getTraceAsString())
        exstr = traceback.format_exc()
        print(exstr)
        # raise e
        print('{"status":"fail", "msg":"exception"}')
        exit(1)



