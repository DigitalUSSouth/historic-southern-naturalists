from flask import Flask, Response
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

Manuscripts = Base.classes.manuscripts
Plants = Base.classes.plants

session = Session(engine)

@app.route('/getAllManuscripts')
def get_manuscripts():
    items = []
    for item in session.query(Manuscripts).order_by(Manuscripts.id):
        items.append({
            'collection':item.collection,
            'compound_page':item.compound_page,
            'contri':item.contri,
            'covera':item.covera,
            'date':item.date,
            'descri':item.descri,
            'image_height':item.image_height,
            'image_width':item.image_width,
            'is_compound_object':item.is_compound_object,
            'media':item.media,
            'parent_object':item.parent_object,
            'pointer':item.pointer,
            'publis':item.publis,
            'relati':item.relati,
            'subjec':item.subjec,
            'title':item.title,
            'transc':item.transc,
            'id':item.id
        })
    json_response = json.dumps(items,ensure_ascii=False,indent=4, sort_keys=True)
    response = Response(json_response)
    response.headers['Content-Type'] = 'application/json'
    return response
