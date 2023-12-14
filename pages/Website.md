Website

* **db_config.php**
This file makes connection to the database.



* **index.php**
This is the default landing page of the website, this file contains the main homepage content. It's the first page visitors see when they visit your site without specifying a particular page. Here you will need to fill in your contact info, in this case your email. 


![index_screenshot](/3EAI-IA-2324-Magazijn/Documentation/index.png)
  


* **validate\_email.php**
When a user fills out the contact form and submits it, this script checks it en redirects to the main page, webshop.php. 


  
* **webshop.php**
This file is the central part of the website that includes the primary content. Here you find all the parts which can be ordered and their info like, price, number of stock etc. Here you can also add the part to your shopping cart. This automatically updates it contents regarding the database contents.


![main_page_screenshot](/3EAI-IA-2324-Magazijn/Documentation/main_page.png)
  

  
* **add\_to\_cart.php**
This file is responsible for handling the logic that allows users to add items to their shopping cart. When a user selects a product and chooses to add it to their cart, this script processes that action, updating the user's cart with the new item(s).


  
* **cart.php**
This file displays the contents of the user's shopping cart. It shows a list of items currently in the cart, along with quantities and here you need to validate your order. 


![cart_screenshot](/3EAI-IA-2324-Magazijn/Documentation/cart.png)
  


* **validate\_order.php**
This validates the order and redirects you back to webshop.php.