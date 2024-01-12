<h3>Thank you for your purchase!</h3>
<h2>Dear {{cache('purchase_data')['customer_name']}}</h2>
<p>I hope this email finds you well. I am writing to inform you that your purchase of {{cache('purchase_data')['product_name']}} is currently awaiting collection. We wanted to remind you to promptly retrieve your item before its expiration date, ensuring you can fully enjoy its benefits.</p>
<p>Thank you for your recent purchase from our store. We are delighted to have you as our valued customer. Your order details are as follows: </p>
<h4>We would like to acknowledge the items you have purchased: </h4>
<ul>
    <li>{{cache('purchase_data')['product_name']}}</li>
    <ul>
        <li>Quantity: {{cache('purchase_data')['product_quantity']}}</li>
        <li>Price: {{cache('purchase_data')["price"]}}</li>
        <li>Total Price: {{cache('purchase_data')["total_amount"]}}</li>
        <p>Order Date: {{cache('purchase_data')['order_date']}}</p>
    </ul>
</li>
</ul>
<br>
<h2>Bussiness Address</h2>
 <ul>
    <li>City: {{cache('purchase_data')['bcity']}}</li>
    <li>Disctrict: {{cache('purchase_data')['bdistrict']}}</li>
    <li>Street or Valige: {{cache('purchase_data')['bvalige']}}</li>
 </ul>
 <br>
 <h3>Bussiness Phone Number</h3>
 <h4>{{cache('purchase_data')['phone']}}</h4>
<p>We appreciate your trust in our products and services. if you have and questions or need further assistance, please don't hestitate to contact our customer support team.</p>
<p>Thank you once again for choosing our store. We look forward to serving you in the future.</p>
<p>Best reguards</p>
<h4>Arzan Ghiza</h4>
