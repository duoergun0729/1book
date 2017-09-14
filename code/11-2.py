from apriori import apriori
from apriori import generateRules
import re

if __name__ == '__main__':
    #myDat = [ [ 1, 3, 4 ], [ 2, 3, 5 ], [ 1, 2, 3, 5 ], [ 2, 5 ] ]
    myDat=[]
    #L, suppData = apriori(myDat, 0.5)
    #rules = generateRules(L, suppData, minConf=0.7)
    #print 'rules:\n', rules
    with open("../data/xss-2000.txt") as f:
        for line in f:
            #/discuz?q1=0&q3=0&q2=0%3Ciframe%20src=http://xxooxxoo.js%3E
            index=line.find("?")
            if index>0:
                line=line[index+1:len(line)]
                #print line
                tokens=re.split('\=|&|\?|\%3e|\%3c|\%3E|\%3C|\%20|\%22|<|>|\\n|\(|\)|\'|\"|;|:|,|\%28|\%29',line)
                #print "token:"
                #print tokens
                myDat.append(tokens)
        f.close()

    L, suppData = apriori(myDat, 0.15)
    rules = generateRules(L, suppData, minConf=0.6)
    #print 'rules:\n', rules