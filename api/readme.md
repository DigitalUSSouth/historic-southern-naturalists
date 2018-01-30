##Steps to run api server in dev mode

- setup virtualenv and required libraries
  -cd to `api`folder
  -`sudo apt install python3-virtualenv`
  -`sudo apt install libmysqlclient-dev`
  -`virtualenv ENV`
- Install required pip packages listed in requirements.txt
  -`pip install flask`
  -`pip install flask-sqlalchemy`
  -`pip install pymysql`
- setup enviroment (cd to `api` folder first)
  -`source ENV/bin/activate`
  -`export FLASK_APP=hsn.py`
  -`export FLASK_DEBUG=1`
- start server in dev mode
  -`flask run --host=0.0.0.0`