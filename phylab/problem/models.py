from django.db import models
from django.contrib.auth.models import User

# Create your models here.

SINGLE_ANSWER_PROBLEM = 1
GAP_FILLING_PROBLEM = 2
CALCULATION_PROBLEM = 3

PROBLEM_TYPE_CHOICES = (
    (SINGLE_ANSWER_PROBLEM, 'Single Answer Problem'),
    (GAP_FILLING_PROBLEM, 'Gap Filling Problem'),
    (CALCULATION_PROBLEM, 'Calculation Problem'),
)

class Problem(models.Model):
    content = models.TextField()
    problem_type = models.IntegerField(choices=PROBLEM_TYPE_CHOICES)
    added_at = models.DateTimeField(auto_now_add=True)
    added_by = models.ForeignKey(User)

class Collection(models.Model):
    user = models.OneToOneField(User)
    like = models.ManyToManyField(Problem, blank=True)
