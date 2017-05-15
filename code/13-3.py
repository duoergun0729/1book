# -*- coding: utf-8 -*-
import networkx as nx
import matplotlib.pyplot as plt


iplist={}
goodiplist={}
#相似度
N=0.5
#黑客团伙IP最少个数
M=3
#黑客IP攻击目标最小个数
R=2

#jarccard系数
def get_len(d1,d2):
    ds1=set()
    for d in d1.keys():
        ds1.add(d)

    ds2=set()
    for d in d2.keys():
        ds2.add(d)
    return len(ds1&ds2)/len(ds1|ds2)

filename="../data/etl-ip-domain-train.txt"
G=nx.Graph()

with open(filename) as f:
    for line in f:
        (ip,domain)=line.split("\t")
        if not ip=="0.0.0.0":
            if not iplist.has_key(ip):
                iplist[ip]={}

            iplist[ip][domain]=1

for ip in iplist.keys():
    if len(iplist[ip]) >= R:
        goodiplist[ip]=1

for ip1 in iplist.keys():
    for ip2 in iplist.keys():
        if not ip1 == ip2 :
            weight=get_len(iplist[ip1],iplist[ip2])
            if (weight >= N) and (ip1 in goodiplist.keys()) and (ip2 in goodiplist.keys()):
                #点不存在会自动添加
                G.add_edge(ip1,ip2,weight=weight)




n_sub_graphs=nx.number_connected_components(G)
sub_graphs=nx.connected_component_subgraphs(G)


for i,sub_graph in enumerate(sub_graphs):
    n_nodes=len(sub_graph.nodes())
    if n_nodes >= M:
        print("Subgraph {0} has {1} nodes {2}".format(i,n_nodes,sub_graph.nodes()))


nx.draw(G)
plt.show()

