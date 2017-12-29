from django.conf.urls import include, url

import problem.views

patterns = [
    url(r'^$', problem.views.problem, name='index'),
    url(r'^get_all_star/$', problem.views.getStarList, name='getAllStar'),
    url(r'^star/$', problem.views.star, name='starQuestion'),
    url(r'^unstar/$', problem.views.unstar, name='cancelStar'),
    url(r'^get_answer/$', problem.views.getAnswer, name='getAnswer'),
    url(r'^demo/$', problem.views.demo, name='demo'),
]


urlpatterns = [
    url(r'^', include(patterns, namespace='problem', app_name='problem')),
]
