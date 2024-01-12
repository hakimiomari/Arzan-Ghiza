<h3>Congratulations! Your Product Has Been Sold</h3>
<h2>Dear {{cache('purchase_data')['bUser_name']}}</h2>
<p>
We are pleased to inform you that your product listed for sale on our platform has been successfully sold. This email serves as confirmation of the sale transaction. </p>
<h3>Product Details:</h3>
<p>Product Name: {{cache('purchase_data')['product_name']}}</p>
<p>Product Price: Price: {{cache('purchase_data')["price"]}}</p>
<p>Buyer: {{cache('purchase_data')["customer_name"]}}</p>
<p>Quantity: {{cache('purchase_data')['product_quantity']}}</p>
<p>Total Amount: {{cache('purchase_data')['total_amount']}}</p>
<h4>We would like to extend our congratulations on the successful sale of your product. We greatly appreciate your participation and trust in our platform.
 </h4>
 <br>
 <h2>Customer Details</h2>
 <ul>
    <li>Customer Name: {{cache('purchase_data')['user']}}</li>
    <li>Email: {{cache('purchase_data')['user_email']}}</li>
    <li>Phone Number: {{cache('purchase_data')['user_phone']}}</li>
 </ul>
 <br>
<p>Thank you for choosing our platform for your selling needs. We wish you continued success in your future endeavors.
</p>
<h5>Best reguards</h5>
<h3>Kamranullah Hakimi</h3>
<h4>Arzan Ghiza</h4>
<h5>hakimikamranullah@gmail.com</h5>
