import jieba
import sys
import json
import flask
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
    return json.dumps({'code': 200, 'strs': "| ".join(strs)})

if __name__ == "__main__":
    server.run(debug=True)