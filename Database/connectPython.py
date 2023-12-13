import pymysql

# Replace these with your actual database connection details
db_config = {
    'host': 'your_host',
    'user': 'your_user',
    'password': 'your_password',
    'database': 'your_database',
}

# Connect to the MySQL server
conn = pymysql.connect(**db_config)

try:
    # Create a cursor object to interact with the database
    with conn.cursor() as cursor:
        # Execute a SQL query to select data from a table
        products = 'SELECT * FROM products'
        cursor.execute(products)

        rekken = 'SELECT * FROM rekken'
        cursor.execute(rekken)

        orders = 'SELECT * FROM ordertable'
        cursor.execute(orders)

        # Fetch all the rows from the result set
        rows = cursor.fetchall()

        # Print the results
        for row in rows:
            print(row)

finally:
    # Close the database connection when done
    conn.close()
