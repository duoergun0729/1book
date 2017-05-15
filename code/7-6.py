# -*- coding:utf-8 -*-

import re
import matplotlib.pyplot as plt
import os
from sklearn.feature_extraction.text import CountVectorizer
from sklearn import cross_validation
import os
from sklearn.naive_bayes import GaussianNB


import pickle
import gzip


def load_data():
    with gzip.open('../data/MNIST/mnist.pkl.gz') as fp:
        training_data, valid_data, test_data = pickle.load(fp)
    return training_data, valid_data, test_data


if __name__ == '__main__':
    training_data, valid_data, test_data=load_data()
    x1,y1=training_data
    x2,y2=test_data
    clf = GaussianNB()
    clf.fit(x1, y1)
    print cross_validation.cross_val_score(clf, x2, y2, scoring="accuracy")





