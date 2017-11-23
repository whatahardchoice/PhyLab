from django.conf.urls import include, url

import report.views

patterns = [
    url(r'^$', report.views.test_index, name='index'),
    url(r'^list/$', report.views.list, name='list'),
    url(r'^getreport/$', report.views.all),
    url(r'^table/$', report.views.table),
    url(r'^create/$', report.views.generate),
]


urlpatterns = [
    url(r'^', include(patterns, namespace='report', app_name='report')),
]
