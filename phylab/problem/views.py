from django.shortcuts import render

# Create your views here.

def problem(request):
    return render(request, 'problem/tiku.html')
