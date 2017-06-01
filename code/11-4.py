import pyfpgrowth


transactions=[]

with open("../data/KnowledgeGraph/sample7.txt") as f:
    for line in f:
        line=line.strip('\n')
        ip,ua,target=line.split(',')
        print "Add (%s %s %s)" % (ip,ua,target)
        transactions.append([ip,ua,target])



patterns = pyfpgrowth.find_frequent_patterns(transactions, 3)
rules = pyfpgrowth.generate_association_rules(patterns, 0.9)

print rules