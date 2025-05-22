// public/js/sales-form.js

class SalesForm {
    constructor() {
        this.itemCounter = 0;
        this.init();
    }

    init() {
        this.bindEvents();
        this.addLineItem(); 
    }

    bindEvents() {
       
        document.getElementById('saleForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitForm();
        });

       
        this.productOptionsTemplate = document.getElementById('productOptionsTemplate').innerHTML;
    }

    addLineItem() {
        this.itemCounter++;
        const tableBody = document.getElementById('itemsTableBody');
        
        const row = document.createElement('tr');
        row.setAttribute('data-item-id', this.itemCounter);
        row.innerHTML = `
            <td>
                <select name="items[${this.itemCounter}][product_id]" class="form-select product-select" required>
                    ${this.productOptionsTemplate}
                </select>
            </td>
            <td>
                <input type="number" name="items[${this.itemCounter}][quantity]" 
                       class="form-control quantity-input" min="1" value="1" required>
            </td>
            <td>
                <input type="number" name="items[${this.itemCounter}][unit_price]" 
                       class="form-control price-input" step="0.01" min="0" required readonly>
            </td>
            <td>
                <input type="number" name="items[${this.itemCounter}][discount_percentage]" 
                       class="form-control discount-input" step="0.01" min="0" max="100" value="0">
            </td>
            <td>
                <span class="line-total">0.00 BDT</span>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="salesForm.removeLineItem(${this.itemCounter})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(row);
        this.bindRowEvents(row);
    }

    bindRowEvents(row) {
        const productSelect = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.price-input');
        const discountInput = row.querySelector('.discount-input');

      
        productSelect.addEventListener('change', (e) => {
            const selectedOption = e.target.selectedOptions[0];
            if (selectedOption && selectedOption.dataset.price) {
                priceInput.value = parseFloat(selectedOption.dataset.price).toFixed(2);
                this.calculateLineTotal(row);
            } else {
                priceInput.value = '';
                this.calculateLineTotal(row);
            }
        });

        // Quantity change
        quantityInput.addEventListener('input', () => {
            this.calculateLineTotal(row);
        });

        // Discount change
        discountInput.addEventListener('input', () => {
            this.calculateLineTotal(row);
        });
    }

    removeLineItem(itemId) {
        const row = document.querySelector(`tr[data-item-id="${itemId}"]`);
        if (row) {
            row.remove();
            this.calculateTotals();
        }
    }

    calculateLineTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.price-input').value) || 0;
        const discountPercentage = parseFloat(row.querySelector('.discount-input').value) || 0;

        const lineTotal = quantity * unitPrice;
        const discountAmount = (lineTotal * discountPercentage) / 100;
        const totalAfterDiscount = lineTotal - discountAmount;

        row.querySelector('.line-total').textContent = this.formatCurrency(totalAfterDiscount);
        
        this.calculateTotals();
    }

    calculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0;

        document.querySelectorAll('#itemsTableBody tr').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.price-input').value) || 0;
            const discountPercentage = parseFloat(row.querySelector('.discount-input').value) || 0;

            if (quantity > 0 && unitPrice > 0) {
                const lineTotal = quantity * unitPrice;
                const discountAmount = (lineTotal * discountPercentage) / 100;
                const totalAfterDiscount = lineTotal - discountAmount;

                subtotal += totalAfterDiscount;
                totalDiscount += discountAmount;
            }
        });

        const taxAmount = subtotal * 0.15; // 15% tax
        const grandTotal = subtotal + taxAmount;

        // Update display
        document.getElementById('subtotal').textContent = this.formatCurrency(subtotal);
        document.getElementById('totalDiscount').textContent = this.formatCurrency(totalDiscount);
        document.getElementById('taxAmount').textContent = this.formatCurrency(taxAmount);
        document.getElementById('grandTotal').innerHTML = '<strong>' + this.formatCurrency(grandTotal) + '</strong>';
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount) + ' BDT';
    }

  
  collectFormData() {
    const data = {
        user_id: document.querySelector('[name="user_id"]').value,
        sale_date: document.querySelector('[name="sale_date"]').value,
        content: document.querySelector('[name="content"]').value,
        items: []
    };

    document.querySelectorAll('#itemsTableBody tr').forEach((row) => {
        const productId = row.querySelector('.product-select').value;
        const quantity = row.querySelector('.quantity-input').value;
        const unitPrice = row.querySelector('.price-input').value;
        const discountPercentage = row.querySelector('.discount-input').value;

        if (productId && quantity && unitPrice) {
            data.items.push({
                product_id: productId,
                quantity: quantity,
                unit_price: unitPrice,
                discount_percentage: discountPercentage || 0
            });
        }
    });

    return data;
}

 async submitForm() {
   

    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Disable submit button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Sale...';

    try {
        const formData = this.collectFormData();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch('/sales', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            this.showAlert('success', data.message);
            setTimeout(() => {
                window.location.href = `/sales`;
            }, 2000);
        } else {
            if (data.errors) {
                let errorMessage = 'Please fix the following errors:\n';
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => {
                        errorMessage += '• ' + error + '\n';
                    });
                });
                alert(errorMessage);
            } else {
                alert('Error: ' + data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while creating the sale. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}


    showAlert(type, message) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.alert-dynamic');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show alert-dynamic`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Insert at top of card body
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(alertDiv, cardBody.firstChild);
    }
}


function addLineItem() {
    salesForm.addLineItem();
}

function removeLineItem(itemId) {
    salesForm.removeLineItem(itemId);
}


document.addEventListener('DOMContentLoaded', function() {
    window.salesForm = new SalesForm();
});

