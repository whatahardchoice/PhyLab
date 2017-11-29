from django.shortcuts import render
from django.http import JsonResponse
from django.conf import settings
import subprocess
import json
import os
import random
import string
from collections import OrderedDict

# Create your views here.

SUCCEED = 'success'
FAILED = 'fail'

module_dir = os.path.dirname(__file__)

def test_index(request):
    return render(request, 'report/base.html')

def list(request):
    reports = json.loads(open(os.path.join(module_dir, 'templates/report/example.json'), 'r').read(), object_pairs_hook=OrderedDict)
    return render(request, 'report/list.html', {'reports': reports['reports']})

def all(request):
    return render(request, 'report/example.json', content_type='application/json')

def table(request):
    experiment_id = request.GET.get('id', None)
    return render(request, 'report/experiment_data_table/{0}.html'.format(experiment_id))

def generate(request):
    experiment_id = request.POST.get('id', None)
    xml = request.POST.get('xml', None)
    rand_name = ''.join(random.sample(string.ascii_letters + string.digits, 26))
    fout = open('report/tmp/{0}.xml'.format(rand_name), 'w')
    fout.write(xml)
    fout.close()
    res_json = {}
    print(settings.BASE_DIR)
    try:
        command = 'python2 report/scripts/handler.py {0} {1} {2}'.format(experiment_id, os.path.join(settings.BASE_DIR, 'report/tmp/{0}.xml'.format(rand_name)), os.path.join(settings.BASE_DIR, 'media/tmp/{0}'.format(rand_name)))
        print(command)
        p = subprocess.Popen(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        res = p.stdout.readlines()
        print(res)
        res = json.loads(res.pop().decode('utf-8'))
        print(res)
        if res['status'] == SUCCEED:
            res_json['status'] = SUCCEED
            res_json['link'] = '/media/tmp/{0}.pdf'.format(rand_name)
        else:
            res_json['status'] = FAILED
            res_json['message'] = res['msg']
    except Exception as e:
        res_json['status'] = FAILED
        res_json['message'] = '生成脚本失败'
    return JsonResponse(res_json)
