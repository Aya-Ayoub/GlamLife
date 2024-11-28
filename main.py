from flask import Flask, jsonify
from flask_cors import CORS, cross_origin
from flask_mysqldb import MySQL

app = Flask(__name__)
cors = CORS(app)

app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'aya'
app.config['MYSQL_PASSWORD'] = 'password'
app.config['MYSQL_DB'] = 'swe'

mysql=MySQL(app)

@app.route('/products', methods=['GET'])
def display_products():
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM Product")
    result = cur.fetchall()
    cur.close()
    return jsonify(result)

if __name__ == '__main__':
    app.run()