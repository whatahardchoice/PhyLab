from django.shortcuts import render
from django.http import JsonResponse
from .models import *
import json

# Create your views here.

def problem(request):
    return render(request, 'problem/tiku.html')

def demo(request):
    return render(request, 'problem/demo.html')

def getStarList(request):
    if request.method == 'POST':
        uid = request.POST.get('uid', None)
        if uid == None:
            return JsonResponse({'OK': False, 'msgs': 'Payload is malformed.'})
        try:
            user = User.objects.get(pk=uid)
        except Exception as e:
            return JsonResponse({'OK': False, 'msgs': 'The user(uid: {0}) does not exist.'.format(uid)})
        res = []
        try:
            collection = Collection.objects.get(user=user)
            problems = collection.like.all()
            for problem in problems:
                content = json.loads(problem.content)
                res.append({'pid': problem.id, 'content': content.get('description', None)})
        except Collection.DoesNotExist as e:
            pass
        
        return JsonResponse({'OK': True, 'list': res, 'msgs': 'OK.'})
    return JsonResponse({'OK': False, 'msgs': 'Request method should be POST.'})

def star(request):
    if request.method == 'POST':
        uid = request.POST.get('uid', None)
        pid = request.POST.get('qid', None)
        if uid == None or pid == None:
            return JsonResponse({'OK': False, 'msgs': 'Payload is malformed.'})
        try:
            user = User.objects.get(pk=uid)
        except Exception as e:
            return JsonResponse({'OK': False, 'msgs': 'The user(uid: {0}) does not exist.'.format(uid)})
        try:
            problem = Problem.objects.get(pk=pid)
        except Exception as e:
            return JsonResponse({'OK': False, 'msgs': 'The problem(pid: {0}) does not exist.'.format(pid)})
        try:
            collection = Collection.objects.get(user=user)
        except Collection.DoesNotExist as e:
            try:
                collection = Collection.objects.create(user=user)
            except Exception as e:
                collection = Collection.objects.get(user=user)

        collection.like.add(problem)
        
        return JsonResponse({'OK': True, 'msgs': 'OK.'})
    return JsonResponse({'OK': False, 'msgs': 'Request method should be POST.'})

def unstar(request):
    if request.method == 'POST':
        uid = request.POST.get('uid', None)
        pid = request.POST.get('qid', None)
        if uid == None or pid == None:
            return JsonResponse({'OK': False, 'msgs': 'Payload is malformed.'})
        try:
            user = User.objects.get(pk=uid)
        except Exception as e:
            return JsonResponse({'OK': False, 'msgs': 'The user(uid: {0}) does not exist.'.format(uid)})
        try:
            problem = Problem.objects.get(pk=pid)
        except Exception as e:
            return JsonResponse({'OK': False, 'msgs': 'The problem(pid: {0}) does not exist.'.format(pid)})
        try:
            collection = Collection.objects.get(user=user)
            collection.like.remove(problem)
        except Collection.DoesNotExist as e:
            pass
        
        return JsonResponse({'OK': True, 'msgs': 'OK.'})
    return JsonResponse({'OK': False, 'msgs': 'Request method should be POST.'})

def getAnswer(request):
    if request.method == 'POST':
        pid = request.POST.get('qid', None)
        try:
            problem = Problem.objects.get(pk=pid)
        except Exception as e:
            return JsonResponse({'OK': False, 'msgs': 'The problem(pid: {0}) does not exist.'.format(pid)})
        content = json.loads(problem.content)
        return JsonResponse({'OK': True, 'answer': content.get('answer', None)})
    return JsonResponse({'OK': False, 'msgs': 'Request method should be POST.'})
