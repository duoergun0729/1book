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
import csv
import matplotlib.pyplot as plt
import os


#处理域名的最小长度
MIN_LEN=10

#状态个数
N=8
#最大似然概率阈值
T=-50

#模型文件名
FILE_MODEL="9-2.m"

def load_alexa(filename):
    domain_list=[]
    csv_reader = csv.reader(open(filename))
    for row in csv_reader:
        domain=row[1]
        if len(domain) >= MIN_LEN:
            domain_list.append(domain)
    return domain_list

def domain2ver(domain):
    ver=[]
    for i in range(0,len(domain)):
        ver.append([ord(domain[i])])
    return ver

def train_hmm(domain_list):
    X = [[0]]
    X_lens = [1]
    for domain in domain_list:
        ver=domain2ver(domain)
        np_ver = np.array(ver)
        X=np.concatenate([X,np_ver])
        X_lens.append(len(np_ver))

    remodel = hmm.GaussianHMM(n_components=N, covariance_type="full", n_iter=100)
    remodel.fit(X,X_lens)
    joblib.dump(remodel, FILE_MODEL)

    return remodel

def load_dga(filename):
    domain_list=[]
    #xsxqeadsbgvpdke.co.uk,Domain used by Cryptolocker - Flashback DGA for 13 Apr 2017,2017-04-13,
    # http://osint.bambenekconsulting.com/manual/cl.txt
    with open(filename) as f:
        for line in f:
            domain=line.split(",")[0]
            if len(domain) >= MIN_LEN:
                domain_list.append(domain)
    return  domain_list

def test_dga(remodel,filename):
    x=[]
    y=[]
    dga_cryptolocke_list = load_dga(filename)
    for domain in dga_cryptolocke_list:
        domain_ver=domain2ver(domain)
        np_ver = np.array(domain_ver)
        pro = remodel.score(np_ver)
        #print  "SCORE:(%d) DOMAIN:(%s) " % (pro, domain)
        x.append(len(domain))
        y.append(pro)
    return x,y

def test_alexa(remodel,filename):
    x=[]
    y=[]
    alexa_list = load_alexa(filename)
    for domain in alexa_list:
        domain_ver=domain2ver(domain)
        np_ver = np.array(domain_ver)
        pro = remodel.score(np_ver)
        #print  "SCORE:(%d) DOMAIN:(%s) " % (pro, domain)
        x.append(len(domain))
        y.append(pro)
    return x, y

def show_hmm():
    domain_list = load_alexa("../data/top-1000.csv")
    if not os.path.exists(FILE_MODEL):
        remodel=train_hmm(domain_list)
    remodel=joblib.load(FILE_MODEL)
    x_3,y_3=test_dga(remodel, "../data/dga-post-tovar-goz-1000.txt")
    x_2,y_2=test_dga(remodel,"../data/dga-cryptolocke-1000.txt")
    x_1,y_1=test_alexa(remodel, "../data/test-top-1000.csv")
    fig,ax=plt.subplots()
    ax.set_xlabel('Domain Length')
    ax.set_ylabel('HMM Score')
    ax.scatter(x_3,y_3,color='b',label="dga_post-tovar-goz",marker='o')
    ax.scatter(x_2, y_2, color='g', label="dga_cryptolock",marker='v')
    ax.scatter(x_1, y_1, color='r', label="alexa",marker='*')
    ax.legend(loc='best')
    plt.show()


def get_aeiou(domain_list):
    x=[]
    y=[]
    for domain in domain_list:
        x.append(len(domain))
        count=len(re.findall(r'[aeiou]',domain.lower()))
        count=(0.0+count)/len(domain)
        y.append(count)
    return x,y

def show_aeiou():
    x1_domain_list = load_alexa("../data/top-1000.csv")
    x_1,y_1=get_aeiou(x1_domain_list)
    x2_domain_list = load_dga("../data/dga-cryptolocke-1000.txt")
    x_2,y_2=get_aeiou(x2_domain_list)
    x3_domain_list = load_dga("../data/dga-post-tovar-goz-1000.txt")
    x_3,y_3=get_aeiou(x3_domain_list)

    fig,ax=plt.subplots()
    ax.set_xlabel('Domain Length')
    ax.set_ylabel('AEIOU Score')
    ax.scatter(x_3,y_3,color='b',label="dga_post-tovar-goz",marker='o')
    ax.scatter(x_2, y_2, color='g', label="dga_cryptolock",marker='v')
    ax.scatter(x_1, y_1, color='r', label="alexa",marker='*')
    ax.legend(loc='best')
    plt.show()

def get_uniq_char_num(domain_list):
    x=[]
    y=[]
    for domain in domain_list:
        x.append(len(domain))
        count=len(set(domain))
        count=(0.0+count)/len(domain)
        y.append(count)
    return x,y

def show_uniq_char_num():
    x1_domain_list = load_alexa("../data/top-1000.csv")
    x_1,y_1=get_uniq_char_num(x1_domain_list)
    x2_domain_list = load_dga("../data/dga-cryptolocke-1000.txt")
    x_2,y_2=get_uniq_char_num(x2_domain_list)
    x3_domain_list = load_dga("../data/dga-post-tovar-goz-1000.txt")
    x_3,y_3=get_uniq_char_num(x3_domain_list)

    fig,ax=plt.subplots()
    ax.set_xlabel('Domain Length')
    ax.set_ylabel('UNIQ CHAR NUMBER')
    ax.scatter(x_3,y_3,color='b',label="dga_post-tovar-goz",marker='o')
    ax.scatter(x_2, y_2, color='g', label="dga_cryptolock",marker='v')
    ax.scatter(x_1, y_1, color='r', label="alexa",marker='*')
    ax.legend(loc='best')
    plt.show()


def count2string_jarccard_index(a,b):
    x=set(' '+a[0])
    y=set(' '+b[0])
    for i in range(0,len(a)-1):
        x.add(a[i]+a[i+1])
    x.add(a[len(a)-1]+' ')

    for i in range(0,len(b)-1):
        y.add(b[i]+b[i+1])
    y.add(b[len(b)-1]+' ')

    return (0.0+len(x-y))/len(x|y)


def get_jarccard_index(a_list,b_list):
    x=[]
    y=[]
    for a in a_list:
        j=0.0
        for b in b_list:
            j+=count2string_jarccard_index(a,b)
        x.append(len(a))
        y.append(j/len(b_list))

    return x,y


def show_jarccard_index():
    x1_domain_list = load_alexa("../data/top-1000.csv")
    x_1,y_1=get_jarccard_index(x1_domain_list,x1_domain_list)
    x2_domain_list = load_dga("../data/dga-cryptolocke-1000.txt")
    x_2,y_2=get_jarccard_index(x2_domain_list,x1_domain_list)
    x3_domain_list = load_dga("../data/dga-post-tovar-goz-1000.txt")
    x_3,y_3=get_jarccard_index(x3_domain_list,x1_domain_list)

    fig,ax=plt.subplots()
    ax.set_xlabel('Domain Length')
    ax.set_ylabel('JARCCARD INDEX')
    ax.scatter(x_3,y_3,color='b',label="dga_post-tovar-goz",marker='o')
    ax.scatter(x_2, y_2, color='g', label="dga_cryptolock",marker='v')
    ax.scatter(x_1, y_1, color='r', label="alexa",marker='*')
    ax.legend(loc='lower right')
    plt.show()

if __name__ == '__main__':
    #show_hmm()
    #show_aeiou()
    #show_uniq_char_num()
    show_jarccard_index()



