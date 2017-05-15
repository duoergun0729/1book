# -*- coding:utf-8 -*-

import sys
import urllib
import urlparse
import re
from hmmlearn import hmm
import numpy as np
from sklearn.externals import joblib
import HTMLParser
import nltk


#处理参数值的最小长度
MIN_LEN=10

#状态个数
N=5
#最大似然概率阈值
T=-200
#字母
#数字 1
#<>,:"'
#其他字符2
SEN=['<','>',',',':','\'','/',';','"','{','}','(',')']

index_wordbag=1 #词袋索引
wordbag={} #词袋

#</script><script>alert(String.fromCharCode(88,83,83))</script>
#<IMG SRC=x onchange="alert(String.fromCharCode(88,83,83))">
#<;IFRAME SRC=http://ha.ckers.org/scriptlet.html <;
#';alert(String.fromCharCode(88,83,83))//\';alert(String.fromCharCode(88,83,83))//";alert(String.fromCharCode(88,83,83))
# //\";alert(String.fromCharCode(88,83,83))//--></SCRIPT>">'><SCRIPT>alert(String.fromCharCode(88,83,83))</SCRIPT>
tokens_pattern = r'''(?x)
 "[^"]+"
|http://\S+
|</\w+>
|<\w+>
|<\w+
|\w+=
|>
|\w+\([^<]+\) #函数 比如alert(String.fromCharCode(88,83,83))
|\w+
'''

def ischeck(str):
    if re.match(r'^(http)',str):
        return False
    for i, c in enumerate(str):
        if ord(c) > 127 or ord(c) < 31:
            return False
        if c in SEN:
            return True
        #排除中文干扰 只处理127以内的字符


    return False


def do_str(line):
    words=nltk.regexp_tokenize(line, tokens_pattern)
    #print  words
    return words

def load_wordbag(filename,max=100):
    X = [[0]]
    X_lens = [1]
    tokens_list=[]
    global wordbag
    global index_wordbag

    with open(filename) as f:
        for line in f:
            line=line.strip('\n')
            #url解码
            line=urllib.unquote(line)
            #处理html转义字符
            h = HTMLParser.HTMLParser()
            line=h.unescape(line)
            if len(line) >= MIN_LEN:
                #print "Learning xss query param:(%s)" % line
                #数字常量替换成8
                line, number = re.subn(r'\d+', "8", line)
                #ulr日换成http://u
                line, number = re.subn(r'(http|https)://[a-zA-Z0-9\.@&/#!#\?:=]+', "http://u", line)
                #干掉注释
                line, number = re.subn(r'\/\*.?\*\/', "", line)
                #print "Learning xss query etl param:(%s) " % line
                tokens_list+=do_str(line)

            #X=np.concatenate( [X,vers])
            #X_lens.append(len(vers))


    fredist = nltk.FreqDist(tokens_list)  # 单文件词频
    keys=fredist.keys()
    keys=keys[:max]
    for localkey in keys:  # 获取统计后的不重复词集
        if localkey in wordbag.keys():  # 判断该词是否已在词袋中
            continue
        else:
            wordbag[localkey] = index_wordbag
            index_wordbag += 1

    print "GET wordbag size(%d)" % index_wordbag
def main(filename):
    X = [[-1]]
    X_lens = [1]
    global wordbag
    global index_wordbag

    with open(filename) as f:
        for line in f:
            line=line.strip('\n')
            #url解码
            line=urllib.unquote(line)
            #处理html转义字符
            h = HTMLParser.HTMLParser()
            line=h.unescape(line)
            if len(line) >= MIN_LEN:
                #print "Learning xss query param:(%s)" % line
                #数字常量替换成8
                line, number = re.subn(r'\d+', "8", line)
                #ulr日换成http://u
                line, number = re.subn(r'(http|https)://[a-zA-Z0-9\.@&/#!#\?:]+', "http://u", line)
                #干掉注释
                line, number = re.subn(r'\/\*.?\*\/', "", line)
                #print "Learning xss query etl param:(%s) " % line
                words=do_str(line)
                vers=[]
                for word in words:
                    #print "ADD %s" % word
                    if word in wordbag.keys():
                        vers.append([wordbag[word]])
                    else:
                        vers.append([-1])

            np_vers = np.array(vers)
            #print np_vers
            X=np.concatenate([X,np_vers])
            X_lens.append(len(np_vers))
            #print X_lens



    remodel = hmm.GaussianHMM(n_components=N, covariance_type="full", n_iter=100)
    remodel.fit(X,X_lens)
    joblib.dump(remodel, "xss-train.pkl")

    return remodel

def test(remodel,filename):
    with open(filename) as f:
        for line in f:
            line = line.strip('\n')
            # url解码
            line = urllib.unquote(line)
            # 处理html转义字符
            h = HTMLParser.HTMLParser()
            line = h.unescape(line)

            if len(line) >= MIN_LEN:
                #print  "CHK XSS_URL:(%s) " % (line)
                    # 数字常量替换成8
                line, number = re.subn(r'\d+', "8", line)
                    # ulr日换成http://u
                line, number = re.subn(r'(http|https)://[a-zA-Z0-9\.@&/#!#\?:]+', "http://u", line)
                    # 干掉注释
                line, number = re.subn(r'\/\*.?\*\/', "", line)
                    # print "Learning xss query etl param:(%s) " % line
                words = do_str(line)
                #print "GET Tokens (%s)" % words
                vers = []
                for word in words:
                        # print "ADD %s" % word
                    if word in wordbag.keys():
                        vers.append([wordbag[word]])
                    else:
                        vers.append([-1])

                np_vers = np.array(vers)
                #print np_vers
                        #print  "CHK SCORE:(%d) QUREY_PARAM:(%s) XSS_URL:(%s) " % (pro, v, line)
                pro = remodel.score(np_vers)

                if pro >= T:
                    print  "SCORE:(%d) XSS_URL:(%s) " % (pro,line)
                        #print line

def test_normal(remodel,filename):
    with open(filename) as f:
        for line in f:
            # 切割参数
            result = urlparse.urlparse(line)
            # url解码
            query = urllib.unquote(result.query)
            params = urlparse.parse_qsl(query, True)

            for k, v in params:
                v=v.strip('\n')
                #print  "CHECK v:%s LINE:%s " % (v, line)

                if len(v) >= MIN_LEN:
                    # print  "CHK XSS_URL:(%s) " % (line)
                    # 数字常量替换成8
                    v, number = re.subn(r'\d+', "8", v)
                    # ulr日换成http://u
                    v, number = re.subn(r'(http|https)://[a-zA-Z0-9\.@&/#!#\?:]+', "http://u", v)
                    # 干掉注释
                    v, number = re.subn(r'\/\*.?\*\/', "", v)
                    # print "Learning xss query etl param:(%s) " % line
                    words = do_str(v)
                    # print "GET Tokens (%s)" % words
                    vers = []
                    for word in words:
                        # print "ADD %s" % word
                        if word in wordbag.keys():
                            vers.append([wordbag[word]])
                        else:
                            vers.append([-1])

                    np_vers = np.array(vers)
                    # print np_vers
                    # print  "CHK SCORE:(%d) QUREY_PARAM:(%s) XSS_URL:(%s) " % (pro, v, line)
                    pro = remodel.score(np_vers)
                    print  "CHK SCORE:(%d) QUREY_PARAM:(%s)" % (pro, v)
                    #if pro >= T:
                        #print  "SCORE:(%d) XSS_URL:(%s) " % (pro, v)
                        #print line

if __name__ == '__main__':
    #test(remodel,sys.argv[2])
    load_wordbag(sys.argv[1],2000)
    #print  wordbag.keys()
    remodel = main(sys.argv[1])
    #test_normal(remodel, sys.argv[2])
    test(remodel, sys.argv[2])

