import re

filename="../data/etl-ip-domain-train.txt"

with open(filename) as f:
    for line in f:
        line=line.strip('\n')
        ip,domain=line.split()

        ip=re.sub(r'\d$', '*', ip)
        domain= re.sub(r'\w{3}$', '*', domain)
        domain = re.sub(r'^\w{3}', '*', domain)

        print "%s\t%s" % (ip,domain)
