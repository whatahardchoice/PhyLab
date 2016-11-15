# -*- coding: utf-8 -*-

import subprocess
import sys
import traceback
import os

available_lab = ['1010113', '1010212', '1070212', '1070312', '1070322', '1080114', '1080124', '1090114']
texdir = '/var/www/phylab/script/tex/'
scriptdir = '/var/www/phylab/script/'
sys.path.append(scriptdir)

if __name__ == '__main__':
	# 三个命令行参数
	# 第一个argv[1]是实验编号，如101031
	# 第二个argv[2]是XML文件所在地址，采用绝对路径
	# 第三个argv[3]是最终生成的pdf文件的目标地址，采用绝对路径


	try:
		latex_head_file = open(texdir + 'Head.tex','r')
		latex_head = latex_head_file.read().decode('utf-8', 'ignore')
		latex_head_file.close();
		latex_tail = "\n\\end{document}"
		latex_body = ""

		flag = True
		for lab in available_lab:
			if (lab == sys.argv[1]):
				#from p1010113 import handler
				eval('from p' + lab + ' import handler')
				latex_body = handler(handler + 'test/' + lab + 'test/' + lab + '.xml')#(sys.argv[2])
				flag = False
				break
		if (flag):
			   print('{"status":"fail", "msg":"no handler"}')

		finish_str = latex_head+latex_body+latex_tail
		finish_file = open(sys.argv[3]+".tex","w")
		finish_file.write(finish_str.encode('utf-8', 'ignore'))
		finish_file.close()

		os.chdir(os.path.dirname(sys.argv[3]))
		#等于１时是错误
		ret =  subprocess.call("pdflatex -interaction=nonstopmode "+sys.argv[3]+".tex",shell=True)
		subprocess.call("rm "+sys.argv[3]+".aux",shell=True)
		subprocess.call("rm "+sys.argv[3]+".synctex*",shell=True)
		subprocess.call("rm "+sys.argv[3]+".log",shell=True)

		if ret==0:
			print('{"status":"success"}')
		else:
			print('{"status":"fail", "msg":"fail to handle"}')
			exit(1)

	except Exception as e:
		#print(e.getTraceAsString())
		exstr = traceback.format_exc()
		print exstr
		#raise e
		print('{"status":"fail", "msg":"exception"}')
		exit(1)
