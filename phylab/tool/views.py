from django.shortcuts import render

# Create your views here.

def tool(request):
    return render(request, 'tool/tool.html')
