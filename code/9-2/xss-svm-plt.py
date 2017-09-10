import re
import numpy as np
from sklearn import preprocessing
from sklearn import cross_validation
from sklearn import datasets
from sklearn import svm
from sklearn.externals import  joblib
import matplotlib.pyplot as plt


x = []
y = []


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
    return [get_len(url),get_url_count(url),get_evil_char(url),get_evil_word(url)]

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

etl('xss-200000.txt',x,1)
etl('good-xss-200000.txt',x,0)

min_max_scaler = preprocessing.MinMaxScaler()
x_min_max=min_max_scaler.fit_transform(x)

plt.scatter(x[1, ], y,'o')
plt.show()

#clf = svm.SVC(kernel='linear', C=1).fit(x_min_max, y)
