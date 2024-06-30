$(document).ready(function () {
    let selectedProducts = [];
    let selectedProductId = null;

    // Generate a 13-digit unique request number
    function generateUniqueRequestNo() {
        return Math.floor(1000000000000 + Math.random() * 9000000000000);
    }

    // Set the initial request number when the page loads


    function renderProductCard(product) {
        return `
            <div class="col-md-4 mb-4">
                <div class="card" data-id="${product.id}" data-type="${product.product_type}" data-barcode="${product.barcode}">
                    <div class="product-images">
                        <div class="image-thumbnails">
                            <img src="${product.back_image}" alt="Back Image">
                            <img src="${product.image_1}" alt="Image 1">
                            <img src="${product.image_2}" alt="Image 2">
                            <img src="${product.image_3}" alt="Image 3">
                        </div>
                        <img src="${product.front_image}" class="card-img-top product-main-image" alt="${product.productnameenglish}">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.productnameenglish}</h5>
                        <p class="card-text">${product.description}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <p class="mb-0">SAR ${product.price}</p>
                            <button class="btn btn-primary mt-2 add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    var stock_request = JSON.parse(document.querySelector('.productListData').getAttribute('data-stock_request'));
    if (stock_request=='edit') {
        selectedProducts = JSON.parse(document.querySelector('.productListData').getAttribute('data-stockRequestData'));
        var request_no = $(".productListData").attr('data-RequestNo');
        $('#request_no').val(request_no);
    }else{
        $('#request_no').val(generateUniqueRequestNo());
    }

// console.log(stock_request)
// console.log(selectedProducts)
    function renderSelectedProductCard(product) {
        return `
            <div class="col-md-4 mb-4">
                <div class="card" data-id="${product.id}">
                    <div class="product-images">
                        <div class="image-thumbnails">
                            <img src="${product.back_image}" alt="Back Image">
                            <img src="${product.image_1}" alt="Image 1">
                            <img src="${product.image_2}" alt="Image 2">
                            <img src="${product.image_3}" alt="Image 3">
                        </div>
                        <img src="${product.front_image}" class="card-img-top product-main-image" alt="${product.name}">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text">${product.description}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <p class="mb-0">SAR ${product.price}</p>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <button class="btn btn-secondary decrease-qty">-</button>
                            <span class="qty">${product.qty}</span>
                            <button class="btn btn-secondary increase-qty">+</button>
                        </div>
                        <button class="btn btn-danger mt-2 remove-from-cart">Remove</button>
                        <input type="hidden" name="product_type[]" value="${product.type}">
                        <input type="hidden" name="barcode[]" value="${product.barcode}">
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
        stepTabs.each(function (index) {
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

    $('#search').on('input', function () {
        const searchQuery = $(this).val().toLowerCase();
        $.ajax({
            url: '/search/products',
            method: 'GET',
            data: { query: searchQuery },
            success: function (response) {
                const productList = $('#product-list');
                productList.html(response.map(product => renderProductCard(product)).join(''));
            }
        });
    });

    $('#product-list').on('click', '.add-to-cart', function () {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        const productType = card.data('type');
        const productBarcode = card.data('barcode');
        const existingProduct = selectedProducts.find(p => p.id === productId);

        if (existingProduct) {
            existingProduct.qty += 1;
            toastr.success('Product quantity increased!');
        } else {
            const newProduct = {
                id: productId,
                name: card.find('.card-title').text(),
                description: card.find('.card-text').text(),
                price: card.find('.mb-0').text().replace('SAR ', ''),
                front_image: card.find('.product-main-image').attr('src'),
                back_image: card.find('.image-thumbnails img:nth-child(1)').attr('src'),
                image_1: card.find('.image-thumbnails img:nth-child(2)').attr('src'),
                image_2: card.find('.image-thumbnails img:nth-child(3)').attr('src'),
                image_3: card.find('.image-thumbnails img:nth-child(4)').attr('src'),
                qty: 1,
                type: productType,
                barcode: productBarcode
            };
            selectedProducts.push(newProduct);
            toastr.success('Product added to cart!');
        }
        updateSelectedProducts();
    });

    // $('#product-list').on('click', '.add-to-cart', function () {
    //     const button = $(this);
    //     const productId = parseInt(button.data('id'));
    //     const existingProduct = selectedProducts.find(p => p.id === productId);

    //     if (existingProduct) {
    //         existingProduct.qty += 1;
    //         toastr.success('Product quantity increased!');
    //     } else {
    //         const newProduct = {
    //             id: productId,
    //             name: button.data('name'),
    //             description: button.data('description'),
    //             price: button.data('price'),
    //             type: button.data('type'),
    //             barcode: button.data('barcode'),
    //             image: button.closest('.card').find('.card-img-top').attr('src'),
    //             qty: 1
    //         };
    //         selectedProducts.push(newProduct);
    //         toastr.success('Product added to cart!');
    //     }
    //     updateSelectedProducts();

    //     // Add hidden fields to the form
    //     const hiddenFieldsContainer = $('#hidden-fields');
    //     hiddenFieldsContainer.append(`
    //         <input type="hidden" name="product_type_${productId}" value="${button.data('type')}">
    //         <input type="hidden" name="barcode_${productId}" value="${button.data('barcode')}">
    //     `);
    // });

    $('#selected-products').on('click', '.increase-qty', function () {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        const product = selectedProducts.find(p => p.id === productId);
        if (product) {
            product.qty += 1;
            card.find('.qty').text(product.qty);
        }
    });

    $('#selected-products').on('click', '.decrease-qty', function () {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        const product = selectedProducts.find(p => p.id === productId);
        if (product && product.qty > 1) {
            product.qty -= 1;
            card.find('.qty').text(product.qty);
        }
    });

    $('#selected-products').on('click', '.remove-from-cart', function () {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        selectedProducts = selectedProducts.filter(p => p.id !== productId);
        card.remove();
        toastr.success('Product removed from cart!');

        // Remove hidden fields from the form
        $(`input[name="product_type_${productId}"]`).remove();
        $(`input[name="barcode_${productId}"]`).remove();
    });

    $('#selected-products,#product-list').on('click', '.view-details', function () {
        const card = $(this).closest('.card');
        const productId = parseInt(card.data('id'));
        const productType = card.data('type');
        selectedProductId = productId;
        if (productType === 'non_gs1') {
            toastr.error('Non GS1 Product has no Digital Link Data');
            return false;
        }

        // Fetch product details and update modal
        $.ajax({
            url: `/products/${selectedProductId}`,
            method: 'GET',
            success: function (response) {
                $('#modalProductName').text(response.product.name);
                $('#modalBrandName').text(response.product.brand);
                $('#modalBarcode').text(response.product.barcode);
                $('#productModal').modal('show');
            }
        });
    });

    $('.btn-icon').on('click', function () {
        const category = $(this).data('category');
        fetchAndRenderData(category);
    });

    // function fetchAndRenderData(category) {
    //     $.ajax({
    //         url: '/products/data',
    //         method: 'POST',
    //         // data: { id: selectedProductId, category: category },
    //         data: { category: category },
    //         success: function(response) {
    //             const table = $('#data-table').DataTable();
    //             table.clear().rows.add(response.data).draw();
    //         }
    //     });
    // }

    $('#request-form').on('submit', function (event) {
        event.preventDefault();
        // console.log("okkk")
        const formData = $(this).serializeArray();
        // console.log(formData)
        const selectedProductsData = selectedProducts.map(product => {
            // console.log(product)
            return { product_id: product.id, productName: product.name, qty: product.qty, product_type: product.type, barcode: product.barcode };
        });

        formData.push({ name: 'selectedProducts', value: JSON.stringify(selectedProductsData) });

        $.ajax({
            url: '/save/stock/transfer/request',
            method: 'POST',
            data: formData,
            success: function (response) {
                toastr.success('Stock Request submitted successfully!');
                // Clear the form and selected products
                $('#request-form')[0].reset();
                selectedProducts = [];
                updateSelectedProducts();
                enableTab(1);
                $('#request_no').val(generateUniqueRequestNo());
                window.location = 'https://pos.gs1ksa.org/stock/transfer/requests'
            },
            error: function () {
                toastr.error('Failed to submit stock request.');
            }
        });
    });

    // $('#product-list, #selected-products').on('click', '.image-thumbnails img', function() {
    //     const mainImage = $(this).closest('.card').find('.product-main-image');
    //     mainImage.attr('src', $(this).attr('src'));
    // });

    $('#product-list, #selected-products').on('click', '.image-thumbnails img', function() {
        const mainImage = $(this).closest('.card').find('.product-main-image');
        const currentMainImageSrc = mainImage.attr('src');
        mainImage.attr('src', $(this).attr('src'));
        $(this).attr('src', currentMainImageSrc);
    });

    function fetchAndRenderData(category) {
        var table = $('#data-table').DataTable({
            "ajax": {
                "url": '/products/data',
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.category = category;
                    return JSON.stringify(d);
                },
                "contentType": "application/json; charset=utf-8"
            },
            processing: true,
            serverSide: true,
            stateSave: true,
            "bDestroy": true,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'target_url', name: 'target_url' },
                { data: 'digital_info_type', name: 'digital_info_type' },
                { data: 'gtin', name: 'gtin' },
            ],
            lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
            pageLength: 10,
            pagingType: 'simple_numbers',
            'columnDefs': [{
                'targets': [0, 1], /* column index */
                'orderable': false, /* true or false */
            }]
        });

    }

    $('#data-table').DataTable();

    $('#go-to-step2').on('click', function () {
        enableTab(2);
    });

    $('#back-to-step1').on('click', function () {
        enableTab(1);
    });

    $('#go-to-step3').on('click', function () {
        enableTab(3);
    });

    $('#back-to-step2').on('click', function () {
        enableTab(2);
    });


});
