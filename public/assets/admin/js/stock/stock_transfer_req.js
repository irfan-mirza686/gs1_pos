$(document).ready(function() {
    let selectedProducts = [];
    let selectedProductId = null;

    function renderProductCard(product) {
        return `
            <div class="col-md-4 mb-4">
                <div class="card" data-id="${product.id}">
                    <img src="${product.image}" class="card-img-top" alt="${product.productnameenglish}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.productnameenglish}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="mt-auto">SR ${product.price}</p>
                        <button class="btn btn-primary mt-2 add-to-cart" data-id="${product.id}" data-name="${product.productnameenglish}" data-description="${product.description}" data-price="${product.price}" data-type="${product.product_type}" data-barcode="${product.barcode}">Add to Cart</button>
                    </div>
                </div>
            </div>
        `;
    }

    function renderSelectedProductCard(product) {
        return `
            <div class="col-md-4 mb-4">
                <div class="card" data-id="${product.id}">
                    <img src="${product.image}" class="card-img-top" alt="${product.name}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">${product.description}</p>
                        <p class="mt-auto">SR ${product.price}</p>
                        <div class="d-flex justify-content-between mt-2">
                            <button class="btn btn-secondary decrease-qty">-</button>
                            <span class="qty">${product.qty}</span>
                            <button class="btn btn-secondary increase-qty">+</button>
                        </div>
                        <button class="btn btn-danger mt-2 remove-from-cart">Remove</button>
                        <button class="btn btn-info mt-2 view-details">View Details</button>
                    </div>
                </div>
            </div>
        `;
    }

    function updateSelectedProducts() {
        const selectedProductList = $('#selected-products');
        selectedProductList.html(selectedProducts.map(product => renderSelectedProductCard(product)).join(''));
    }

    function enableTab(step) {
        const stepTabs = $('.nav-link');
        stepTabs.each(function(index) {
            if (index < step) {
                $(this).removeClass('active').addClass('disabled');
            } else if (index === step - 1) {
                $(this).addClass('active').removeClass('disabled');
            } else {
                $(this).removeClass('active').removeClass('disabled');
            }
        });
        $('#step' + step + '-tab').tab('show');
    }

    $('#search').on('input', function() {
        const searchQuery = $(this).val().toLowerCase();
        $.ajax({
            url: '/search/products',
            method: 'GET',
            data: { query: searchQuery },
            success: function(response) {
                const productList = $('#product-list');
                productList.html(response.map(product => renderProductCard(product)).join(''));
            }
        });
    });

    $('#product-list').on('click', '.add-to-cart', function() {
        const button = $(this);
        const productId = parseInt(button.data('id'));
        const existingProduct = selectedProducts.find(p => p.id === productId);

        if (existingProduct) {
            existingProduct.qty += 1;
            toastr.success('Product quantity increased!');
        } else {
            const newProduct = {
                id: productId,
                name: button.data('name'),
                description: button.data('description'),
                price: button.data('price'),
                type: button.data('type'),
                barcode: button.data('barcode'),
                image: button.closest('.card').find('.card-img-top').attr('src'),
                qty: 1
            };
            selectedProducts.push(newProduct);
            toastr.success('Product added to cart!');
        }
        updateSelectedProducts();

        // Add hidden fields to the form
        const hiddenFieldsContainer = $('#hidden-fields');
        hiddenFieldsContainer.append(`
            <input type="hidden" name="product_type_${productId}" value="${button.data('type')}">
            <input type="hidden" name="barcode_${productId}" value="${button.data('barcode')}">
        `);
    });

    $('#selected-products').on('click', '.increase-qty', function() {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        const product = selectedProducts.find(p => p.id === productId);
        if (product) {
            product.qty += 1;
            card.find('.qty').text(product.qty);
        }
    });

    $('#selected-products').on('click', '.decrease-qty', function() {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        const product = selectedProducts.find(p => p.id === productId);
        if (product && product.qty > 1) {
            product.qty -= 1;
            card.find('.qty').text(product.qty);
        }
    });

    $('#selected-products').on('click', '.remove-from-cart', function() {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        selectedProducts = selectedProducts.filter(p => p.id !== productId);
        card.remove();
        toastr.success('Product removed from cart!');

        // Remove hidden fields from the form
        $(`input[name="product_type_${productId}"]`).remove();
        $(`input[name="barcode_${productId}"]`).remove();
    });

    $('#selected-products').on('click', '.view-details', function() {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        selectedProductId = productId;

        // Fetch product details and update modal
        $.ajax({
            url: `/products/${selectedProductId}`,
            method: 'GET',
            success: function(response) {
                $('#modalProductName').text(response.product.name);
                $('#modalBrandName').text(response.product.brand);
                $('#modalBarcode').text(response.product.barcode);
                $('#productModal').modal('show');
            }
        });
    });

    $('.btn-icon').on('click', function() {
        const category = $(this).data('category');
        fetchAndRenderData(category);
    });

    function fetchAndRenderData(category) {
        $.ajax({
            url: '/products/data',
            method: 'GET',
            data: { id: selectedProductId, category: category },
            success: function(response) {
                const table = $('#data-table').DataTable();
                table.clear().rows.add(response.data).draw();
            }
        });
    }

    $('#data-table').DataTable();

    $('#go-to-step2').on('click', function() {
        enableTab(2);
    });

    $('#back-to-step1').on('click', function() {
        enableTab(1);
    });

    $('#go-to-step3').on('click', function() {
        enableTab(3);
    });

    $('#back-to-step2').on('click', function() {
        enableTab(2);
    });

    $('#order-form').on('submit', function(event) {
        event.preventDefault();

        const formData = $(this).serializeArray();
        const selectedProductsData = selectedProducts.map(product => {
            console.log(product)
            return { product_id: product.id, productName: product.name, qty: product.qty, product_type: product.type, barcode: product.barcode };
        });

        formData.push({ name: 'selectedProducts', value: JSON.stringify(selectedProductsData) });

        $.ajax({
            url: '/save/stock/transfer/request',
            method: 'POST',
            data: formData,
            success: function(response) {
                toastr.success('Order submitted successfully!');
                // Clear the form and selected products
                $('#order-form')[0].reset();
                selectedProducts = [];
                updateSelectedProducts();
                enableTab(1);
            },
            error: function() {
                toastr.error('Failed to submit order.');
            }
        });
    });
});
