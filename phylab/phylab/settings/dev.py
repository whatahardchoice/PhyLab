# -*- coding: utf-8 -*-

# THIS IS FOR DEVELOPMENT ENVIRONMENT
# DO NOT USE IT IN PRODUCTION

# Create your own dev_local.py
# import * this module there and use it like this:
# python manage.py runserver --settings=phylab.settings.dev_local

from __future__ import unicode_literals

from .base import *


DEBUG = True

TEMPLATES[0]['OPTIONS']['debug'] = True
# TEMPLATES[0]['OPTIONS']['string_if_invalid'] = '\{\{%s\}\}'  # Some Django templates relies on this being the default

ADMINS = (('Leo Jacob', 'john@example.com'), )  # Log email to console when DEBUG = False

SECRET_KEY = "DEV"

ALLOWED_HOSTS = ['127.0.0.1', '192.168.0.103', '192.168.0.102', '10.138.47.165', '10.138.30.192']

# Django Debug Toolbar settings

INTERNAL_IPS = ['127.0.0.1', '192.168.0.103', '192.168.0.102', '10.138.47.165', ]


DEBUG_TOOLBAR_CONFIG = {
    'JQUERY_URL': '',
}

MIDDLEWARE_CLASSES.extend([
    'debug_toolbar.middleware.DebugToolbarMiddleware',
])

INSTALLED_APPS.extend([
   'debug_toolbar',
])

# Database
# https://docs.djangoproject.com/en/1.11/ref/settings/#databases

DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.sqlite3',
        'NAME': os.path.join(BASE_DIR, 'db.sqlite3'),
    }
}

CACHES.update({
    'default': {
        'BACKEND': 'django.core.cache.backends.locmem.LocMemCache',
    },
    'st_rate_limit': {
        'BACKEND': 'django.core.cache.backends.locmem.LocMemCache',
        'LOCATION': 'spirit_rl_cache',
        'TIMEOUT': None
    }
})

PASSWORD_HASHERS = [
    'django.contrib.auth.hashers.MD5PasswordHasher',
]

EMAIL_BACKEND = 'django.core.mail.backends.console.EmailBackend'

DEBUG_TOOLBAR_PANELS = [
    'debug_toolbar.panels.versions.VersionsPanel',
    'debug_toolbar.panels.timer.TimerPanel',
    'debug_toolbar.panels.settings.SettingsPanel',
    'debug_toolbar.panels.headers.HeadersPanel',
    'debug_toolbar.panels.request.RequestPanel',
    'debug_toolbar.panels.sql.SQLPanel',
    'debug_toolbar.panels.templates.TemplatesPanel',
    'debug_toolbar.panels.staticfiles.StaticFilesPanel',
    'debug_toolbar.panels.cache.CachePanel',
    'debug_toolbar.panels.signals.SignalsPanel',
    'debug_toolbar.panels.logging.LoggingPanel',
    'debug_toolbar.panels.redirects.RedirectsPanel',
    'debug_toolbar.panels.profiling.ProfilingPanel',
]
