import re
import matplotlib.pyplot as plt
import os
from sklearn.feature_extraction.text import CountVectorizer
from sklearn import cross_validation
import os

import numpy as np
from sklearn.neural_network import MLPClassifier



def get_feature(line):
    v=[]
    return v


def load_sqlinject(fillname):
    x=[]
    with open(fillname) as f:
        for line in f:
            line=line.strip('\n')
            x.append(get_feature(line))

if __name__ == '__main__':
    print "Hello KNN webshell"
    load_sqlinject("../data/sql-10000.txt")