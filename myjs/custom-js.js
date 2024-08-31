
   
    
    document.addEventListener("DOMContentLoaded", function() {
        const msgbox = document.querySelector("#messagebox");
        const successAlert = document.querySelector(".alert-success");
        const dangerAlert = document.querySelector(".alert-danger");
        const customerid = document.getElementById("customerid");
        const paymentid = document.getElementById("paymentid");
        const orderid = document.getElementById("orderid");
        const receiptnumber = document.getElementById("receiptnumber");
        const orderstatus = document.getElementById("orderstatus");
        const paymentstatus = document.getElementById("paymentstatus");
        const selectcustomer = document.getElementById("ordercustomer");
        const totalcost= document.getElementById("totalcost");
        const amountowed= document.getElementById("amountowed");
        const paystatus= document.getElementById("paymentstat");
        
        const orderdate= document.getElementById("orderdate");
        const amountpaid= document.getElementById("amountpaid");

        customerid.value = localStorage.getItem("customerid");
        paymentid.value = localStorage.getItem("paymentid");
        orderid.value = localStorage.getItem("orderid");
        receiptnumber.value = localStorage.getItem("receiptnumber");
        paystatus.innerHTML=localStorage.getItem("paymentstatus");
        totalcost.value = localStorage.getItem("totalcost");
        amountowed.value = localStorage.getItem("amountowed");
        orderdate.value = localStorage.getItem("orderdate");
        paymentstatus.addEventListener("change",()=>{
            if(paymentstatus.checked){
        paystatus.innerHTML="Paid"
        paymentstatus.value="paid";
        
       }else{
        paystatus.innerHTML=localStorage.getItem("paymentstatus");
       }
        })
       

function updateSummary(){
const balance= document.getElementById("balance");
balance.value=parseFloat(amountowed.value-amountpaid.value).toFixed(2);
localStorage.setItem("balance",balance.value);
}

document.getElementById('paymentForm').addEventListener('input', updateSummary);
        document.getElementById('paymentForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            const paymentmethod= document.querySelector('input[name="paymentmethod"]:checked').value
        
            const amountPaid = parseFloat(formData.get('amountpaid'));
           

            
            const formobj = {
                customerid: localStorage.getItem("customerid"),
                amountpaid: amountPaid.toFixed(2),
                orderstatus: localStorage.getItem("orderstatus"),
                paymentstatus: paymentstatus.value,
                orderid:orderid.value,
                amountpaid:amountPaid,
                balance: localStorage.getItem("balance"),
                paymentmethod:paymentmethod

            };

            for (const key in formobj) {
                if (formobj.hasOwnProperty(key)) {
                    localStorage.setItem(key, formobj[key]);
                }
            }

            try {
                const response = await fetch('../api/payment/process.makepayment.php', {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(formobj)
                });
                const data = await response.json();
                if (data.success ) {
                    localStorage.setItem('receiptnumber', data['receiptnumber']);
                    successAlert.hidden = false;
                    successAlert.classList.remove("alert-danger");
                    successAlert.classList.add("alert-success");
                    msgbox.innerHTML = data.message;
                   // msgbox.innerHTML = data.order.message;
                   

                    this.reset();
                    setTimeout(() => {
                        successAlert.hidden = true;
                       // window.location.href = "orderstable.php"
                    }, 2000);
                } else {
                    dangerAlert.hidden = false;
                    dangerAlert.classList.remove("alert-success");
                    dangerAlert.classList.add("alert-danger");
                    msgbox.innerHTML = Array.isArray(data.payment.message) ? data.payment.message.join("<br>") : data.payment.message;
                }
               
            } catch (error) {
                console.error(error);
                dangerAlert.hidden = false;
                dangerAlert.classList.remove("alert-success");
                dangerAlert.classList.add("alert-danger");
                msgbox.innerHTML = error.message;
            }
        });
       
       /*     document.getElementById("addItemBtn").addEventListener("click",addItemRow);
        function addItemRow() {
            const orderItems = document.getElementById('orderItems');
            const itemCount = orderItems.querySelectorAll('.item-row').length + 1;
            const fooditem= document.getElementById("fooditem"); 
        fooditem.value=localStorage.getItem("fooditem")
        const quantity= document.getElementById("quantity");
        quantity.value=localStorage.getItem("quantity")
        const price= document.getElementById("price");
        price.value=localStorage.getItem("price")
            const itemRow = document.createElement('div');
            itemRow.classList.add('form-row', 'item-row');
            itemRow.innerHTML = `
                <div class="form-group col-md-6">
                    <label for="item">Item</label>
                     <input type="text" class="form-control item-input" min="0" id="fooditem" name="fooditem" value="${fooditem}" required>
              
                </div>
                <div class="form-group col-md-2">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control quantity-input" min="0" id="quantity" name="quantity" value="${quantity}" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" class="form-control price-input" id="price" name="price" value="${price}" readonly>
                </div>
               
            `;

            const itemSelect = createItemSelect(itemCount);
            itemRow.querySelector('.form-group.col-md-6').appendChild(itemSelect);
            orderItems.appendChild(itemRow);
        } */
            function showAlert(type, message) {
                let alertBox, messageBox, soundFile;
                if (type === 'success') {
                    alertBox = document.getElementById('success-alert');
                    messageBox = document.getElementById('success-message');
                    soundFile = 'success-sound.mp3'; // Add path to your success sound file
                } else if (type === 'danger') {
                    alertBox = document.getElementById('danger-alert');
                    messageBox = document.getElementById('danger-message');
                    soundFile = 'failure-sound.mp3'; // Add path to your failure sound file
                }
                
                messageBox.textContent = message;
                alertBox.style.display = 'block';
                
                // Play sound
                let audio = new Audio(soundFile);
                audio.play();
                
                // Hide after 3 seconds
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000);
            }
        
            // Example Usage:
            // showAlert('success', 'Operation completed successfully!');
            // showAlert('danger', 'There was an error processing your request.');
        
    });