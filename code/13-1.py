from neo4j.v1 import GraphDatabase, basic_auth

driver = GraphDatabase.driver("bolt://localhost", auth=basic_auth("neo4j", "maidou"))
session = driver.session()

# Insert data
insert_query = '''
UNWIND {pairs} as pair
MERGE (p1:Person {name:pair[0]})
MERGE (p2:Person {name:pair[1]})
MERGE (p1)-[:KNOWS]-(p2);
'''

data = [["Jim","Mike"],["Jim","Billy"],["Anna","Jim"],
          ["Anna","Mike"],["Sally","Anna"],["Joe","Sally"],
          ["Joe","Bob"],["Bob","Sally"]]

session.run(insert_query, parameters={"pairs": data})

# Friends of a friend

foaf_query = '''
MATCH (person:Person)-[:KNOWS]-(friend)-[:KNOWS]-(foaf)
WHERE person.name = {name}
  AND NOT (person)-[:KNOWS]-(foaf)
RETURN foaf.name AS name
'''

print 1
results = session.run(foaf_query, parameters={"name": "Joe"})
for record in results:
    print(record["name"])


# Common friends

common_friends_query = """
MATCH (user:Person)-[:KNOWS]-(friend)-[:KNOWS]-(foaf:Person)
WHERE user.name = {user} AND foaf.name = {foaf}
RETURN friend.name AS friend
"""

print 2
results = session.run(common_friends_query, parameters={"user": "Joe", "foaf": "Sally"})
for record in results:
    print(record["friend"])

# Connecting paths

connecting_paths_query = """
MATCH path = shortestPath((p1:Person)-[:KNOWS*..6]-(p2:Person))
WHERE p1.name = {name1} AND p2.name = {name2}
RETURN path
"""

print 3
results = session.run(connecting_paths_query, parameters={"name1": "Joe", "name2": "Billy"})
for record in results:
    print (record["path"])


session.close()