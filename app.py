from flask import Flask, render_template

app = Flask(__name__)

# Route for the home page
@app.route('/')
def home():
    return "Welcome to Glamelife!"

# Route for the Product Page
@app.route('/product')
def product():
    return render_template('product.html')

# Route for the Cart Page
@app.route('/cart')
def cart():
    return render_template('cart.html')

if __name__ == '__main__':
    app.run(debug=True)
