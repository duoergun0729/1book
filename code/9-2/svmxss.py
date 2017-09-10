import re
import numpy as np
from sklearn.feature_extraction.text import CountVectorizer

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


def etl_vc(f1,f2):
    try:
        x=[]
        y=[]

        file_object = open(f1)
        lines=file_object.readlines()
        for i in range(0,len(lines)):
            if isxss:
                y.append(1)
            else:
                y.append(0)
        cv = CountVectorizer()
        cv_fit = cv.fit_transform(lines)
        x=cv_fit.toarray()

    finally:
        file_object.close( )


        return (x,y)

x=[]
y=[]
(x,y)=etl_vc('xss-50.txt',1)
print x,y

