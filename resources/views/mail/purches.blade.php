<h3>Thank you for your purchase!</h3>
<h2>Dear {{cache('purchase_data')['customer_name']}}</h2>
<p>Thank you for your recent purchase from our store. We are delighted to have you as our valued customer. Your order details are as follows: </p>
<p>Order Date: {{cache('purchase_data')['order_date']}}</p>
<h4>We would like to acknowledge the items you have purchased: </h4>
<ul>
   <li>{{cache('purchase_data')['product_name']}}</li>
    <ul>
      <li>Quantity: {{cache('purchase_data')['product_quantity']}}</li>
      <li>Price: {{cache('purchase_data')["price"]}}</li>
      <li>Total Amount: {{cache('purchase_data')['total_amount']}}</li>
    </ul>
  </li>
</ul>
<h2>Delivery</h2>
<p>I am writing to inform you that our team at {{cache('purchase_data')['bUser_name']}} is thrilled to be delivering our exceptional services to you. We deeply appreciate your trust in our expertise and are committed to providing you with an outstanding experience.</p>
<br>
<p>We appreciate your trust in our products and services. if you have and questions or need further assistance, please don't hestitate to contact our customer support team.</p>
<p>Thank you once again for choosing our store. We look forward to serving you in the future.</p>
<h2>Phone Number of {{cache('purchase_data')['bUser_name']}}</h2>
<h5>{{cache('purchase_data')['phone']}}</h5>
<p>Best reguards</p>
<h4>Arzan Ghiza</h4>
