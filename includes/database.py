import json, requests, pprint

url =  "http://CdmServer.com:5432/dmwebservices/index.php?q=dmGetCollectionFieldInfo/historic_southern_naturalists/json"

data = requests.get(url=url)
binary = data.content
output = json.loads(binary)

pprint.pprint(output)

