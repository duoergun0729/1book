import re

from neo4j.v1 import GraphDatabase, basic_auth

nodes={}
index=1

driver = GraphDatabase.driver("bolt://localhost:7687",auth=basic_auth("neo4j","maidou"))
session = driver.session()

file_object = open('r-graph.txt', 'r')
try:
    for line in file_object:
        matchObj = re.match( r'(\S+) -> (\S+)', line, re.M|re.I)
    if matchObj:
        path = matchObj.group(1);
        ref = matchObj.group(2);
    if path in nodes.keys():
        path_node = nodes[path]
    else:
        path_node = "Page%d" % index
        nodes[path]=path_node
    sql = "create (%s:Page {url:\"%s\" , id:\"%d\",in:0,out:0})" %(path_node,path,index)
    index=index+1
    session.run(sql)
    #print sql
    if ref in nodes.keys():
        ref_node = nodes[ref]
    else:
        ref_node = "Page%d" % index
        nodes[ref]=ref_node
    sql = "create (%s:Page {url:\"%s\",id:\"%d\",in:0,out:0})" %(ref_node,ref,index)
    index=index+1
    session.run(sql)
    #print sql
    sql = "create (%s)-[:IN]->(%s)" %(path_node,ref_node)
    session.run(sql)
    #print sql
    sql = "match (n:Page {url:\"%s\"}) SET n.out=n.out+1" % path
    session.run(sql)
    #print sql
    sql = "match (n:Page {url:\"%s\"}) SET n.in=n.in+1" % ref
    session.run(sql)
    #print sql
finally:
     file_object.close( )

session.close()
