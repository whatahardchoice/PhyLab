from django.conf.urls import include, url

import problem.views

patterns = [
    url(r'^$', problem.views.problem, name='index'),
]


urlpatterns = [
    url(r'^', include(patterns, namespace='problem', app_name='problem')),
]
