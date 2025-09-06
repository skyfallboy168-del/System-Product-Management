document.addEventListener('DOMContentLoaded', function() {
    const addItemBtn = document.getElementById('add-item-btn');
    const itemsTableBody = document.querySelector('#quote-items tbody');
    const subtotalEl = document.getElementById('subtotal');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('total');

    let itemIndex = 0;

    // Function to create a new item row
    function createItemRow() {
        const row = document.createElement('tr');

        // Product Cell
        const productCell = document.createElement('td');
        const productSelect = document.createElement('select');
        productSelect.name = `items[${itemIndex}][product_id]`;
        productSelect.classList.add('product-select');
        productSelect.innerHTML = '<option value="">Select a product</option>';
        productsData.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = product.name;
            option.dataset.price = product.selling_price_1;
            productSelect.appendChild(option);
        });
        productCell.appendChild(productSelect);

        // Quantity Cell
        const qtyCell = document.createElement('td');
        const qtyInput = document.createElement('input');
        qtyInput.type = 'number';
        qtyInput.name = `items[${itemIndex}][quantity]`;
        qtyInput.value = 1;
        qtyInput.min = 1;
        qtyInput.classList.add('quantity-input');
        qtyCell.appendChild(qtyInput);

        // Price Cell
        const priceCell = document.createElement('td');
        const priceSpan = document.createElement('span');
        priceSpan.classList.add('unit-price');
        priceSpan.textContent = '0.00';
        priceCell.appendChild(priceSpan);

        // Total Cell
        const totalCell = document.createElement('td');
        const totalSpan = document.createElement('span');
        totalSpan.classList.add('line-total');
        totalSpan.textContent = '0.00';
        totalCell.appendChild(totalSpan);

        // Action Cell
        const actionCell = document.createElement('td');
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.textContent = 'Remove';
        removeBtn.classList.add('remove-item-btn');
        actionCell.appendChild(removeBtn);

        row.appendChild(productCell);
        row.appendChild(qtyCell);
        row.appendChild(priceCell);
        row.appendChild(totalCell);
        row.appendChild(actionCell);

        itemsTableBody.appendChild(row);
        itemIndex++;
    }

    // Function to update all totals
    function updateTotals() {
        let subtotal = 0;
        const rows = itemsTableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const lineTotal = parseFloat(row.querySelector('.line-total').textContent);
            if (!isNaN(lineTotal)) {
                subtotal += lineTotal;
            }
        });

        // For now, tax is 0. This can be expanded later.
        const tax = 0;
        const total = subtotal + tax;

        subtotalEl.textContent = subtotal.toFixed(2);
        taxEl.textContent = tax.toFixed(2);
        totalEl.textContent = total.toFixed(2);
    }

    // Event listener for adding items
    addItemBtn.addEventListener('click', createItemRow);

    // Event delegation for item rows
    itemsTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    itemsTableBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('tr');
            const productSelect = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.quantity-input');
            const priceSpan = row.querySelector('.unit-price');
            const totalSpan = row.querySelector('.line-total');

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = selectedOption.dataset.price || 0;
            const quantity = qtyInput.value || 0;

            priceSpan.textContent = parseFloat(price).toFixed(2);
            totalSpan.textContent = (price * quantity).toFixed(2);

            updateTotals();
        }
    });
});
