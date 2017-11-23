from django.test import TestCase
import json

# Create your tests here.


def exampleExpListJson():
    data = {}
    data['reports'] = {

        '1011': [
            {
                'id': 1010113,
                'experimentName': '拉伸法测钢丝弹性模型扭摆法测定转动惯量',
            },
            {
                'id': 1010212,
                'experimentName': '扭摆法测量转动惯量',
            },
        ],

        '1021': [
            {
                'id': 1020113,
                'experimentName': '测定冰的熔解热及电热法测量焦耳热的当量',
            },
        ],

        '1061': [
            {
                'id': 1060111,
                'experimentName': '物距像距法测量透镜焦距',
            },
            {
                'id': 1060213,
                'experimentName': '自准直法测量透镜焦距',
            },
        ],

        '1071': [
            {
                'id': 1070212,
                'experimentName': '测量三棱镜的顶角',

            },
            {
                'id': 1070312,
                'experimentName': '最小偏向角法测量棱镜的折射率',
            },
            {
                'id': 1070322,
                'experimentName': '掠入射法测量棱镜的折射率',

            },
        ],

        '1081': [
            {
                'id': 1080114,
                'experimentName': '激光双棱镜干涉',
            },
            {
                'id': 1080124,
                'experimentName': '激光劳埃镜干涉',
            },
        ],

        '1082': [
            {
                'id': 1080215,
                'experimentName': '钠光双棱镜干涉',
            },
            {
                'id': 1080225,
                'experimentName': '钠光劳埃镜干涉',
            },
        ],

        '1091': [
            {
                'id': 1090114,
                'experimentName': '迈克尔逊干涉',
            },
        ],

    }

    return json.dumps(data, indent=4)
