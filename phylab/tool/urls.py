from django.conf.urls import include, url

import tool.views

patterns = [
    url(r'^$', tool.views.tool, name='index'),
]


urlpatterns = [
    url(r'^', include(patterns, namespace='tool', app_name='tool')),
]
