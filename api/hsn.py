from flask import Flask
from pprint import pprint
import json
app = Flask(__name__)

#setup database
from sqlalchemy.ext.automap import automap_base
from sqlalchemy.orm import Session
from sqlalchemy import create_engine

Base = automap_base()
engine = create_engine("mysql+pymysql://user:p@localhost/hsn")

# reflect the tables
Base.prepare(engine, reflect=True)

# mapped classes are now created with names by default
# matching that of the table name.
Manuscripts = Base.classes.manuscripts
Plants = Base.classes.plants

session = Session(engine)

@app.route('/getAllManuscripts')
def get_manuscripts():
    response = []
    for doc in session.query(Manuscripts).order_by(Manuscripts.id):
        response.append({'id':doc.id,'title':doc.title})
    json_response = json.dumps(response,ensure_ascii=False,indent=4, sort_keys=True)
    return json_response

@app.route('/getAllPlants')
def get_plants():
    response = []
    for doc in session.query(Plants).order_by(Plants.id):
        response.append({'id':doc.id,'title':doc.title})
    json_response = json.dumps(response,ensure_ascii=False,indent=4, sort_keys=True)
    return json_response
