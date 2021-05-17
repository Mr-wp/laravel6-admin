import jieba
import sys
import json
import requests
import flask
from translate import Translator
from flask import request,redirect,url_for #想获取到请求参数的话，就得用这个
server = flask.Flask(__name__) #把这个python文件当做一个web服务
from flask_mail import Mail
app = flask.Flask(__name__)
mail = Mail(app)

#爬虫模拟的浏览器头部信息
agent = 'MMozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0'
headers = {
        "User-Agent": agent
        }

@server.route('/getSplitWords', methods=['GET', 'POST'])
def getSplitWords():
    # 打印语言默认编码
    print(f"defaultencoding--{sys.getdefaultencoding()}")
    # 打印系统配置的编码
    print(f"filesystemencoding--{sys.getfilesystemencoding()}")
    old_str = request.values.get('title')
    strs = jieba.cut(old_str, cut_all=False)
    return json.dumps({'code': 200, 'strs': "|".join(strs)})

@server.route('/translatetozn', methods=['GET', 'POST'])
def translatetozn():
    # 以下是将简单句子从英语翻译中文
    translator = Translator(to_lang="chinese")
    word_str = request.values.get('word')
    # translation = translator.translate(word_str)
    # return json.dumps({'code': 200, 'result': translation})
    word_list = word_str.split(',')
    result = []
    for word in word_list:
        if judge_pure_english(word):
            translation = translator.translate(word)
            result.append(translation)
    return json.dumps({'code': 200, 'result': result})

@server.route('/translate_youdao', methods=['GET', 'POST'])
def translate_youdao():
    """
    input : str 需要翻译的字符串
    output：translation 翻译后的字符串
    有每小时1000次访问的限制
    """
    # API
    url = 'http://fanyi.youdao.com/translate?smartresult=dict&smartresult=rule&smartresult=ugc&sessionFrom=null'
    # 传输的参数， i为要翻译的内容
    word_str = request.values.get('word')
    word_list = []
    words_arr = word_str.split(',')
    for word in words_arr:
        if judge_pure_english(word):
            key = {
                'type': "AUTO",
                'i': word,
                "doctype": "json",
                "version": "2.1",
                "keyfrom": "fanyi.web",
                "ue": "UTF-8",
                "action": "FY_BY_CLICKBUTTON",
                "typoResult": "true"
            }
            # key 这个字典为发送给有道词典服务器的内容
            response = requests.post(url, data=key)
            # 判断服务器是否相应成功
            if response.status_code == 200:
                # 通过 json.loads 把返回的结果加载成 json 格式
                result = json.loads(response.text)
                translation = result['translateResult'][0][0]['tgt']
                word_list.append(translation)
    return json.dumps({'code': 200, 'result': word_list})

def judge_pure_english(keyword):
    return all(ord(c) < 128 for c in keyword)

if __name__ == "__main__":
    server.run(debug=True)