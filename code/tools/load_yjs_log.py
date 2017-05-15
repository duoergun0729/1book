import csv
import re
import json


filename="/Users/liu.yan/Downloads/query_result-1.csv"

with open(filename,'rb') as csvfile:
    reader = csv.DictReader(csvfile)
    for row in reader:
        event_urlparams=row["fenghuolun_3_0_requests.event_urlparams"]
        #fhl_edge_haswaf = row["fenghuolun_3_0_requests.fhl_edge_haswaf"]
        #fhl_edge_waf_label=row["fenghuolun_3_0_requests.fhl_edge_waf_rulemsg"]
        #if len(fhl_edge_waf_label) <= 0 :
        #print event_urlparams
        #print event_urlparams
        #{"rand":"94749219","page":"13","aid":"448"}
        jsons = json.loads(event_urlparams)
        for key in jsons:
            print jsons[key]



