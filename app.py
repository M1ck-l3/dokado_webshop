from flask import Flask, request, render_template, redirect, url_for, session
import mysql.connector
from functools import wraps

app = Flask(__name__)
app.secret_key = 'your_secret_key'

def get_db_connection():
    conn = mysql.connector.connect(
        host="localhost",
        user="admin_username", # still needs to be replaced with admin username
        password="admin_password", # still needs to be replaced with admin password
        database="dokado_db"
    )
    return conn

def login_required(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if 'user_id' not in session:
            return redirect(url_for('login'))
        return f(*args, **kwargs)
    return decorated_function

def admin_required(f):
    @wraps(f)
    def decorated_function(*args, **kwargs):
        if 'user_role' not in session or session['user_role'] != 1:
            return redirect(url_for('index'))
        return f(*args, **kwargs)
    return decorated_function

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        cursor.execute('SELECT * FROM users WHERE username = %s AND password = %s', (username, password))
        user = cursor.fetchone()
        conn.close()
        
        if user:
            session['user_id'] = user['id']
            session['user_role'] = user['role']
            return redirect(url_for('index'))
        else:
            return 'Invalid credentials'
    
    return render_template('login.html')

@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('index'))

@app.route('/')
def index():
    return 'Welcome to the home page. <a href="/login">Login</a>'

@app.route('/dames_kleding')
def dames_kleding():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute('SELECT * FROM products')
    products = cursor.fetchall()
    conn.close()
    return render_template('dames_kleding.html', products=products)

@app.route('/heren_kleding')
def heren_kleding():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute('SELECT * FROM products')
    products = cursor.fetchall()
    conn.close()
    return render_template('heren_kleding.html', products=products)

@app.route('/add', methods=('GET', 'POST'))
@admin_required
def add_product():
    if request.method == 'POST':
        name = request.form['name']
        price = request.form['price']
        picture = request.form['picture']
        
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute('INSERT INTO products (name, price, picture) VALUES (%s, %s, %s)', (name, price, picture))
        conn.commit()
        conn.close()
        return redirect(url_for('index'))
    
    return render_template('add_product.html')

@app.route('/edit/<int:id>', methods=('GET', 'POST'))
@admin_required
def edit_product(id):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute('SELECT * FROM products WHERE id = %s', (id,))
    product = cursor.fetchone()
    
    if request.method == 'POST':
        name = request.form['name']
        price = request.form['price']
        picture = request.form['picture']
        
        cursor.execute('UPDATE products SET name = %s, price = %s, picture = %s WHERE id = %s', (name, price, picture, id))
        conn.commit()
        conn.close()
        return redirect(url_for('index'))
    
    conn.close()
    return render_template('edit_product.html', product=product)

@app.route('/delete/<int:id>', methods=('POST',))
@admin_required
def delete_product(id):
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute('DELETE FROM products WHERE id = %s', (id,))
    conn.commit()
    conn.close()
    return redirect(url_for('index'))

if __name__ == '__main__':
    app.run(debug=True)

    

# login/ register page

@app.route('/register', methods=['POST'])
def register():
    username = request.form['new_username']
    password = request.form['new_password']
    
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute('INSERT INTO users (username, password, role) VALUES (%s, %s, %s)', (username, password, 0))  # Role 0 for regular users
    conn.commit()
    conn.close()
    
    return redirect(url_for('login'))
