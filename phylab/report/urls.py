from django.conf.urls import include, url

import report.views

patterns = [
    url(r'^$', report.views.list, name='index'),
    url(r'^list/$', report.views.all, name='list'),
    url(r'^data_table/$', report.views.table, name='data-table'),
    url(r'^generate/$', report.views.generate, name='generate'),
]


urlpatterns = [
    url(r'^', include(patterns, namespace='report', app_name='report')),
]
