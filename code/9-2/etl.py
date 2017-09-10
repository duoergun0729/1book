import re
import numpy as np
from sklearn import cross_validation
from sklearn import datasets
from sklearn import svm
from sklearn.externals import  joblib
from sklearn import metrics

from svmxss import get_last_char
from svmxss import get_evil_word
from svmxss import get_evil_char
from svmxss import get_len
from svmxss import get_feature

x = []
y = []

"""
def get_len(url):
    return len(url)

def get_url_count(url):
    if re.search('(http://)|(https://)', url, re.IGNORECASE) :
        return 1
    else:
        return 0

def get_evil_char(url):
    return len(re.findall("[<>,\'\"/]", url, re.IGNORECASE))

def get_evil_word(url):
    return len(re.findall("(alert)|(script=)(%3c)|(%3e)|(%20)|(onerror)|(onload)|(eval)|(src=)|(prompt)",url,re.IGNORECASE))

def get_last_char(url):
    if re.search('/$', url, re.IGNORECASE) :
        return 1
    else:
        return 0

def get_feature(url):
    return [get_len(url),get_url_count(url),get_evil_char(url),get_evil_word(url),get_last_char(url)]
"""
def etl(filename,data,isxss):
    try:
        file_object = open(filename)
        for line in file_object:
            f1=get_len(line)
            f2=get_url_count(line)
            f3=get_evil_char(line)
            f4=get_evil_word(line)
            data.append([f1,f2,f3,f4])
            if isxss:
                y.append(1)
            else:
                y.append(0)
    finally:
        file_object.close( )
    return data

#etl('xss-50000.txt',x,1)
#etl('good-xss-50000.txt',x,0)
etl('xss-200000.txt',x,1)
etl('good-xss-200000.txt',x,0)


#x_train, x_test, y_train, y_test = cross_validation.train_test_split(x,y, test_size=0.4, random_state=0)

#clf = svm.SVC(kernel='linear', C=1).fit(x_train, y_train)
clf=joblib.load("xss-svm-50000-module.m")

y_test=[]


#for a in x:
#    y_test.append(clf.predict(a))

y_test=clf.predict(x)

print metrics.accuracy_score(y_test, y)



"""
#with open("good-xss-200000.txt") as f:
with open("waf-access.log") as f:
    for line in f:
#clf.predict([[2., 2.]])
        predict=clf.predict(get_feature(line))
        if predict == 1:
            print("maybe guest error xss %s") % (line)
"""